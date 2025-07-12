<h1>Appointment Notification</h1>

<p>Hello {{ $appointment->client->name }},</p>

<p>
    This is to notify you about your appointment scheduled on 
    <strong>{{ \Carbon\Carbon::parse($appointment->start_time)->format('F j, Y') }}</strong> 
    at <strong>{{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}</strong> until
    <strong>{{ \Carbon\Carbon::parse($appointment->finish_time)->format('F j, Y') }}</strong> 
    at <strong>{{ \Carbon\Carbon::parse($appointment->finish_time)->format('g:i A') }}</strong>.
</p>

<p>
    Status: <strong>{{ ucfirst($appointment->status->name ?? 'Pending') }}</strong> 
</p>

<p>Thank you,<br>
Teraju</p>
