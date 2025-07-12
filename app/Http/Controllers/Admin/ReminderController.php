<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AppointmentReminder;
use Illuminate\Support\Facades\Mail;
use App\Appointment;

class ReminderController extends Controller
{
    public function sendReminder($id)
    {
        $appointment = Appointment::findOrFail($id);

        Mail::to($appointment->email)->send(new AppointmentReminder($appointment));

        return back()->with('success', 'Reminder email sent!');
    }
}

