@extends('layouts.admin')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.appointments.index') }}">Appointments</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ trans('global.create') }}</li>
@endsection
@section('content')

<div class="card">
    <div class="card-body">
        <form action="{{ route("admin.appointments.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="client_id" value="{{ old('client_id', isset($clients) && $clients ? $clients->id : '') }}">
            <div class="form-group {{ $errors->has('department_id') ? 'has-error' : '' }}">
                <label for="department">{{ trans('cruds.department.title') }} <span style="color: red;">*</span></label>
                <select name="department_id" id="department" class="form-control select2" required>
                    @foreach($departments as $id => $department)
                        <option value="{{ $id }}" {{ (old('department_id', $selectedDepartment ?? '') == $id) ? 'selected' : '' }}>
                            {{ $department }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group {{ $errors->has('employee_id') ? 'has-error' : '' }}">
                <label for="employee">{{ trans('cruds.appointment.fields.employee') }} <span style="color: red;">*</span></label>
                <select name="employee_id" id="employee" class="form-control select2" required>
                    @foreach($employees as $id => $employee)
                        <option value="{{ $id }}" {{ (old('employee_id', $selectedEmployee ?? '') == $id) ? 'selected' : '' }}>
                            {{ $employee }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date Picker -->
            <div class="form-group {{ $errors->has('start_time') ? 'has-error' : '' }}">
                <label for="appointment_date">{{ trans('cruds.appointment.fields.date_appointment') }} <span style="color: red;">*</span></label>
                <input type="date" id="appointment_date" name="appointment_date" class="form-control"
                    value="{{ old('appointment_date', isset($appointment) ? \Carbon\Carbon::parse($appointment->start_time)->format('Y-m-d') : '') }}"
                    required>
                @if($errors->has('start_time'))
                    <em class="invalid-feedback">{{ $errors->first('start_time') }}</em>
                @endif
            </div>

            <!-- Time Slot Badges -->
            <div class="form-group {{ $errors->has('time_slot') ? 'has-error' : '' }}">
                <label>{{ trans('cruds.appointment.fields.time_slot') }} <span style="color: red;">*</span></label>
                <div class="d-flex flex-wrap gap-2">
                    @php
                        $slots = [
                            '09:00:00-10:00:00' => '9AM - 10AM',
                            '11:00:00-12:00:00' => '11AM - 12PM',
                            '14:00:00-15:00:00' => '2PM - 3PM'
                        ];
                    @endphp
                    @foreach($slots as $value => $label)
                        <label class="btn btn-outline-primary">
                            <input type="radio" name="time_slot" value="{{ $value }}" class="d-none"
                                {{ old('time_slot', isset($appointment) ? ($appointment->start_time->format('H:i:s') . '-' . $appointment->finish_time->format('H:i:s')) : '') == $value ? 'checked' : '' }}>
                            <span class="badge bg-primary text-white p-2">{{ $label }}</span>
                        </label>
                        <span>&nbsp</span>
                    @endforeach
                </div>
                @if($errors->has('time_slot'))
                    <em class="invalid-feedback">{{ $errors->first('time_slot') }}</em>
                @endif
            </div>
            
            <div class="form-group {{ $errors->has('comments') ? 'has-error' : '' }}">
                <label for="comments">{{ trans('cruds.appointment.fields.comments') }} <span style="color: red;">*</span></label>
                <textarea id="comments" name="comments" class="form-control" required>{{ old('comments', isset($appointment) ? $appointment->comments : '') }}</textarea>
                @if($errors->has('comments'))
                    <em class="invalid-feedback">
                        {{ $errors->first('comments') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.appointment.fields.comments_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-success" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('#department').change(function () {
            var departmentID = $(this).val();
            $('#employee').html('<option value="">{{ trans('global.pleaseSelect') }}</option>');

            if (departmentID) {
                $.ajax({
                    url: "{{ route('admin.employees.byDepartment', '') }}/" + departmentID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $.each(data, function (key, value) {
                            $('#employee').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            }
        });

    });
    document.addEventListener('DOMContentLoaded', function () {
        const slotRadios = document.querySelectorAll('input[name="time_slot"]');
        
        slotRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                // Remove active from all
                document.querySelectorAll('.btn-outline-primary').forEach(btn => {
                    btn.classList.remove('active');
                });

                // Add active to selected
                this.closest('label').classList.add('active');
            });

            // Trigger once if already checked (e.g. on page load with old value)
            if (radio.checked) {
                radio.closest('label').classList.add('active');
            }
        });
    });
    $('#appointment_date, #employee').on('change', function () {
    const employeeId = $('#employee').val();
    const date = $('#appointment_date').val();

    if (employeeId && date) {
        $.ajax({
            url: "{{ route('admin.appointments.checkSlots') }}",
            method: 'GET',
            data: {
                employee_id: employeeId,
                date: date
            },
            success: function (response) {
                // Re-enable all first
                $('input[name="time_slot"]').prop('disabled', false).closest('label').removeClass('disabled');

                // Disable booked ones
                response.booked_slots.forEach(slot => {
                    const input = $('input[name="time_slot"][value="' + slot + '"]');
                    input.prop('disabled', true);
                    input.closest('label').addClass('disabled');
                });
            }
        });
    }
});

</script>

@endsection

