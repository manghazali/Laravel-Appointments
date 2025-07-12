<?php

namespace App\Http\Controllers\Admin;

use App\Appointment;
use App\Client;
use App\Employee;
use App\Mail\AppointmentReminder;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAppointmentRequest;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Status;
use App\Department;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Mail;

class AppointmentsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user(); 
        $roleIds = $user->roles->pluck('id');
        $employee = Employee::where('user_id', auth()->id())->first();
        $client = Client::where('user_id', auth()->id())->first();
        
        if ($request->ajax()) {
            if ($roleIds->contains(1)) {
                $query = Appointment::with(['client', 'employee', 'status'])->select(sprintf('%s.*', (new Appointment)->table));
            } elseif ($roleIds->contains(2)) {
                $query = Appointment::with(['client', 'employee', 'status'])->where('employee_id', $employee->id)->select(sprintf('%s.*', (new Appointment)->table));
            } elseif ($roleIds->contains(3)) {
                $query = Appointment::with(['client', 'employee', 'status'])->where('client_id', $client->id)->select(sprintf('%s.*', (new Appointment)->table));
            } else {
                abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
            }
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'appointment_show';
                $editGate      = 'appointment_edit';
                $deleteGate    = 'appointment_delete';
                $crudRoutePart = 'appointments';
                $cancelGate    = 'appointment_cancel';
                $employee_access    = 'employee_access';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'cancelGate',
                    'crudRoutePart',
                    'employee_access',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->addColumn('client_name', function ($row) {
                return $row->client ? $row->client->name : '';
            });

            $table->addColumn('employee_name', function ($row) {
                return $row->employee ? $row->employee->name : '';
            });

            $table->editColumn('comments', function ($row) {
                return $row->comments ? $row->comments : "";
            });

            $table->editColumn('status', function ($row) {
                $labels = [];

                $statuses = is_iterable($row->status) ? $row->status : [$row->status];

                foreach ($statuses as $status) {
                    if (!$status) {
                        continue;
                    }
                    if (strtolower($status->name) === 'pending') {
                        $class = 'bg-warning';
                    } elseif (strtolower($status->name) === 'approved') {
                        $class = 'bg-success';
                    } elseif (strtolower($status->name) === 'rejected' || strtolower($status->name) === 'canceled') {
                        $class = 'bg-danger';
                    } else {
                        $class = 'bg-secondary';
                    }
                    $labels[] = sprintf('<span class="badge rounded-pill %s">%s</span>', $class, $status->name);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'client', 'employee', 'status']);

            return $table->make(true);
        }

        return view('admin.appointments.index');
    }

    public function create()
    {
        abort_if(Gate::denies('appointment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user = auth()->user();
        
        $selectedDepartment = request('department_id');
        $selectedEmployee = request('employee_id');

        $clients = Client::where('user_id', $user->id)->first();

        $employees = Employee::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $departments = Department::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $status = Status::all()->pluck('name', 'id');

        return view('admin.appointments.create', compact('clients', 'employees', 'status', 'departments', 'selectedDepartment', 'selectedEmployee'));
    }

    public function store(StoreAppointmentRequest $request)
    {
        $date = $request->input('appointment_date');
        $slot = explode('-', $request->input('time_slot'));

        $start_time = $date . ' ' . $slot[0];
        $finish_time = $date . ' ' . $slot[1];

        $appointment = Appointment::create([
            'client_id'    => $request->input('client_id'),
            'employee_id'  => $request->input('employee_id'),
            'department_id'=> $request->input('department_id'),
            'status_id'    => $request->input('status_id'), // if using status
            'start_time'   => $start_time,
            'finish_time'  => $finish_time,
            'comments'     => $request->input('comments'),
        ]);
        $appointment->status_id = $request->input('status', Status::where('name', 'pending')->value('id'));
        $appointment->save();

        $client_id = $request->input('client_id');
        $clients = Client::where('id', $client_id)->first();

        // Send reminder email
        // Mail::to($clients->email)->send(new AppointmentReminder($appointment));

        return redirect()->route('admin.appointments.index');
    }

    public function edit(Appointment $appointment)
    {
        abort_if(Gate::denies('appointment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clients = Client::where('id', $appointment->client_id)->first();

        $employees = Employee::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $departments = Department::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $status = Status::whereIn('name', ['Approved', 'Rejected','Pending'])->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $appointment->load('client', 'employee', 'status');

        return view('admin.appointments.edit', compact('clients', 'employees', 'departments', 'status', 'appointment'));
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $date = $request->input('appointment_date');
        $slot = explode('-', $request->input('time_slot'));

        $start_time = $date . ' ' . $slot[0];
        $finish_time = $date . ' ' . $slot[1];

        $data = [
            'client_id'    => $request->input('client_id'),
            'employee_id'  => $request->input('employee_id'),
            'status_id'    => $request->input('status_id'), // if using status
            'start_time'   => $start_time,
            'finish_time'  => $finish_time,
            'comments'     => $request->input('comments'),
        ];
        $appointment->update($data);

        return redirect()->route('admin.appointments.index');
    }

    public function show(Appointment $appointment)
    {
        abort_if(Gate::denies('appointment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $appointment->load('client', 'employee', 'status');

        return view('admin.appointments.show', compact('appointment'));
    }

    public function destroy(Appointment $appointment)
    {
        abort_if(Gate::denies('appointment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $appointment->delete();

        return back();
    }

    public function massDestroy(MassDestroyAppointmentRequest $request)
    {
        Appointment::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function cancel(Request $request)
    {
        $id = $request->input('id');
        $cancel_reason = $request->input('cancel_reason');
        $appointment = Appointment::find($id);
        $appointment->cancel_reason = $cancel_reason;
        $appointment->save();
        $appointment->status_id = Status::where('name', 'canceled')->value('id');
        $appointment->save();

        return view('admin.appointments.index');
    }

    public function checkSlots(Request $request)
    {
        $employeeId = $request->input('employee_id');
        $date = $request->input('date');

        $appointments = Appointment::where('employee_id', $employeeId)
            ->whereDate('start_time', $date)
            ->get();

        $bookedSlots = $appointments->map(function ($appointment) {
            return $appointment->start_time->format('H:i:s') . '-' . $appointment->finish_time->format('H:i:s');
        });

        return response()->json(['booked_slots' => $bookedSlots]);
    }

    public function completed(Request $request)
    {
        $id = $request->input('id');
        $completed_reason = $request->input('completed_reason');
        $appointment = Appointment::find($id);
        $appointment->completed_reason = $completed_reason;
        $appointment->save();
        $appointment->status_id = Status::where('name', 'completed')->value('id');
        $appointment->save();

        return view('admin.appointments.index');
    }

}
