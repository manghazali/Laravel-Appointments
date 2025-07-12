<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Carbon;
use App\Appointment;
use App\Employee;
use App\Client;

class HomeController
{
    public function index()
    {
        $user = auth()->user();
        $clients = Client::where('user_id', $user->id)->first();

        // Initialize all variables with default values
        $todayAppointmentsCount = 0;
        $tomorrowAppointmentsCount = 0;
        $pendingAppointmentsCount = 0;
        $cancelAppointmentsCount = 0;
        $approvedAppointmentsCount = 0;
        $totalAppointmentsCount = 0;
        $employeesWithTodayAppointments = collect(); // empty collection
        $pendingAppointmentsToday = collect();

        if ($clients) {
            $todayAppointmentsCount = Appointment::where('client_id', $clients->id)
                ->whereDate('start_time', Carbon::today())
                ->count();

            $tomorrowAppointmentsCount = Appointment::where('client_id', $clients->id)
                ->whereDate('start_time', Carbon::tomorrow())
                ->count();

            $pendingAppointmentsCount = Appointment::where('client_id', $clients->id)
                ->whereHas('status', fn($query) => $query->where('name', 'Pending'))
                ->count();

            $approvedAppointmentsCount = Appointment::where('client_id', $clients->id)
                ->whereHas('status', fn($query) => $query->where('name', 'Approved'))
                ->count();

            $cancelAppointmentsCount = Appointment::where('client_id', $clients->id)
                ->whereHas('status', fn($query) => $query->where('name', 'Canceled'))
                ->count();

            $totalAppointmentsCount = Appointment::where('client_id', $clients->id)->count();
        } else {
            $todayAppointmentsCount = Appointment::whereDate('start_time', Carbon::today())->count();

            $tomorrowAppointmentsCount = Appointment::whereDate('start_time', Carbon::tomorrow())->count();

            $pendingAppointmentsCount = Appointment::whereHas('status', fn($query) => $query->where('name', 'Pending'))->count();

            $employeesWithTodayAppointments = Employee::whereHas('appointments', function ($query) {
                $query->whereDate('start_time', Carbon::today());
            })->with(['appointments' => function ($query) {
                $query->whereDate('start_time', Carbon::today());
            }])->get();

            $pendingAppointmentsToday = Appointment::whereDate('start_time', Carbon::today())
                ->whereHas('status', fn($query) => $query->where('name', 'Pending'))
                ->get();
        }

        return view('home', compact(
            'todayAppointmentsCount',
            'tomorrowAppointmentsCount',
            'employeesWithTodayAppointments',
            'pendingAppointmentsCount',
            'cancelAppointmentsCount',
            'approvedAppointmentsCount',
            'totalAppointmentsCount',
            'pendingAppointmentsToday'
        ));
    }

}
