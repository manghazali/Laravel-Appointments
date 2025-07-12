<?php

namespace App\Http\Controllers\Admin;

use App\Employee;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyEmployeeRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Status;
use App\Department;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class EmployeesController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request, User $user)
    {
        $user = auth()->user(); 
        $roleIds = $user->roles->pluck('id');

        if ($request->ajax()) {
            if ($roleIds->contains(1)) {
                $query = Employee::with(['status'])->select(sprintf('%s.*', (new Employee)->table));
            }elseif ($roleIds->contains(2)) {
                $query = Employee::with(['status'])->where('user_id', $user->id)->select(sprintf('%s.*', (new Employee)->table));
            }
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'employee_show';
                $editGate      = 'employee_edit';
                $deleteGate    = 'employee_delete';
                $crudRoutePart = 'employees';

                return view('partials.datatablesActionsDefault', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : "";
            });
            $table->editColumn('phone', function ($row) {
                return $row->phone ? $row->phone : "";
            });
            $table->editColumn('photo', function ($row) {
                if ($photo = $row->photo) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }

                return '';
            });
            $table->editColumn('status', function ($row) {

                if($row->status->name == 'Active'){
                    $labels = sprintf('<span class="badge badge-success">%s</span>', $row->status->name);
                }else if($row->status->name == 'Inactive'){

                    $labels = sprintf('<span class="badge badge-danger">%s</span>', $row->status->name);
                }
                return $labels;
            });

            $table->rawColumns(['actions', 'placeholder', 'photo', 'status']);

            return $table->make(true);
        }

        return view('admin.employees.index');
    }

    public function create()
    {
        abort_if(Gate::denies('employee_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $status = Status::all()->pluck('name', 'id');

        $departments = Department::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.employees.create', compact('status', 'departments'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        $data = $request->all();
        $data['status_id'] = $request->input('status_id', Status::where('name', 'Active')->value('id'));
        $employee = Employee::create($data);

        if ($request->input('photo', false)) {
            $employee->addMedia(storage_path('tmp/uploads/' . $request->input('photo')))->toMediaCollection('photo');
        }
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make('password'),
        ]);

        $user->roles()->sync(2); // 2 = user role
        
        $employee->update(['user_id' => $user->id]);

        return redirect()->route('admin.employees.index');
    }

    public function edit(Employee $employee)
    {
        abort_if(Gate::denies('employee_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $status = Status::whereIn('name', ['Active', 'Inactive'])->pluck('name', 'id');
        $departments = Department::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $employee->load('status');

        return view('admin.employees.edit', compact('status', 'employee', 'departments'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->all());

        if ($request->input('photo', false)) {
            if (!$employee->photo || $request->input('photo') !== $employee->photo->file_name) {
                $employee->addMedia(storage_path('tmp/uploads/' . $request->input('photo')))->toMediaCollection('photo');
            }
        } elseif ($employee->photo) {
            $employee->photo->delete();
        }

        return redirect()->route('admin.employees.index');
    }

    public function show(Employee $employee)
    {
        abort_if(Gate::denies('employee_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employee->load('status');

        return view('admin.employees.show', compact('employee'));
    }

    public function destroy(Employee $employee)
    {
        abort_if(Gate::denies('employee_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employee->delete();

        return back();
    }

    public function massDestroy(MassDestroyEmployeeRequest $request)
    {
        Employee::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
  
    public function getByDepartment($department_id)
    {
        $employees = Employee::where('department_id', $department_id)
            ->pluck('name', 'id');

        return response()->json($employees);
    }
}
