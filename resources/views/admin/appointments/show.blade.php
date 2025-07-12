@extends('layouts.admin')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.appointments.index') }}">Appointment</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ trans('global.show') }}</li>
@endsection
@section('content')

<div class="card">

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.appointment.fields.client') }}
                        </th>
                        <td>
                            {{ $appointment->client->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th width="20%">
                            {{ trans('cruds.department.title') }}
                        </th>
                        <td>
                            {{ $appointment->employee->department->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th width="20%">
                            {{ trans('cruds.appointment.fields.employee') }}
                        </th>
                        <td>
                            {{ $appointment->employee->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.appointment.fields.start_time') }}
                        </th>
                        <td>
                            {{ $appointment->start_time->format('d/m/Y h:i A') }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.appointment.fields.finish_time') }}
                        </th>
                        <td>
                            {{ $appointment->finish_time->format('d/m/Y h:i A') }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.appointment.fields.comments') }}
                        </th>
                        <td>
                            {!! $appointment->comments !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.appointment.fields.status') }}
                        </th>
                        <td>
                            {{ $appointment->status->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.appointment.fields.created_at') }}
                        </th>
                        <td>
                            {{ $appointment->created_at->format('d/m/Y') }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default btn-sm" href="{{ url()->previous() }}">
                <i class="fas fa-arrow-left"></i> {{ trans('global.back_to_list') }}
            </a>
        </div>


    </div>
</div>
@endsection