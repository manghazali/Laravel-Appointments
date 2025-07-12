@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="row col-lg-6">
            <div class="col-lg-6">
                <div class="card text-white bg-primary">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <h6 class="card-title">Appointments Today</h6>
                            <h3 class="card-text"><strong>{{ $todayAppointmentsCount }}</strong></h3>
                        </div>
                        <div class="ml-auto d-flex align-items-center justify-content-center" style="font-size: 3rem; color: rgba(255, 255, 255, 0.8);">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6"> 
                <div class="card text-white bg-secondary">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <h6 class="card-title">Appointments Tomorrow</h6>
                            <h3 class="card-text"><strong>{{ $tomorrowAppointmentsCount }}</strong></h3>
                        </div>
                        <div class="ml-auto d-flex align-items-center justify-content-center" style="font-size: 3rem; color: rgba(255, 255, 255, 0.8);">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
            @can('user_management_access')
            <div class="col-lg-6">
                <div class="card text-black">
                    <div class="card-header">
                        <h5 class="card-title">Unavailable Today</h5>
                    </div>
                    <div class="card-body">
                        <div class="scrollable-content" style="max-height: 300px; overflow-y: auto;">
                            <ul class="list-unstyled">
                                @foreach($employeesWithTodayAppointments as $employee)
                                <li>
                                    • {{ $employee->name }}
                                    @foreach($employee->appointments->where('start_time', '>=', \Carbon\Carbon::today()->startOfDay())
                                    ->where('start_time', '<=', \Carbon\Carbon::today()->endOfDay()) as $appointment)
                                        <br>
                                        <small class="badge badge-warning">Start: {{ $appointment->start_time->format('h:i A') }}, End: {{ $appointment->finish_time->format('h:i A') }}</small>
                                    @endforeach
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card text-black">
                    <div class="card-header">
                        <h5 class="card-title">Appointments Not Approved Yet</h5>
                    </div>
                    <div class="card-body">
                        <div class="scrollable-content" style="max-height: 300px; overflow-y: auto;">
                        <ul class="list-unstyled">
                            @foreach($pendingAppointmentsToday as $pendingAppointment)
                            <li>
                                • {{ $pendingAppointment->employee->name }}
                                <br>
                                <small class="badge badge-info">{{ $pendingAppointment->start_time->format('d/m/Y') }}</small>
                                <small class="badge badge-info">Start: {{ $pendingAppointment->start_time->format('h:i A') }}</small><br>
                                <small class="badge badge-info">{{ $pendingAppointment->finish_time->format('d/m/Y') }}</small>
                                <small class="badge badge-info">End: {{ $pendingAppointment->finish_time->format('h:i A') }}</small>
                            </li>
                            @endforeach
                        </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
        </div>
        <div class="row col-lg-6">
            <div class="col-lg-6">
                <div class="card text-white bg-warning">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <h6 class="card-title">Pending Appointments</h6>
                            <h3 class="card-text"><strong>{{ $pendingAppointmentsCount }}</strong></h3>
                        </div>
                        <div class="ml-auto d-flex align-items-center justify-content-center" style="font-size: 3rem; color: rgba(255, 255, 255, 0.8);">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card text-white bg-danger">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <h6 class="card-title">Cancel Appointments</h6>
                            <h3 class="card-text"><strong>{{ $cancelAppointmentsCount }}</strong></h3>
                        </div>
                        <div class="ml-auto d-flex align-items-center justify-content-center" style="font-size: 3rem; color: rgba(255, 255, 255, 0.8);">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card text-black bg-success">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <h6 class="card-title">Approved Appointments</h6>
                            <h3 class="card-text"><strong>{{ $approvedAppointmentsCount }}</strong></h3>
                        </div>
                        <div class="ml-auto d-flex align-items-center justify-content-center" style="font-size: 3rem; color: rgba(255, 255, 255, 0.8);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card text-white bg-info">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <h6 class="card-title">Total Appointments</h6>
                            <h3 class="card-text"><strong>{{ $totalAppointmentsCount }}</strong></h3>
                        </div>
                        <div class="ml-auto d-flex align-items-center justify-content-center" style="font-size: 3rem; color: rgba(255, 255, 255, 0.8);">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent

@endsection