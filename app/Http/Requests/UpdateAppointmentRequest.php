<?php

namespace App\Http\Requests;

use App\Appointment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('appointment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'client_id'   => [
                'required',
                'integer',
            ],
            'appointment_date' => [
                'required',
                'date_format:Y-m-d',
            ],
            'time_slot' => [
                'required',
                'regex:/^\d{2}:\d{2}:\d{2}-\d{2}:\d{2}:\d{2}$/', // e.g., 10:00:00-11:00:00
            ],
            'status.*'  => [
                'integer',
            ],
            'status'    => [
                'array',
            ],
        ];
    }
}
