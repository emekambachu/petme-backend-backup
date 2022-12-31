<img src="{{ @config('app.url').'logo.png' }}" width="100"/><br>

<p>
    Hello {{ $name }}<br>
    You got a new appointment request from {{ $user_name }}.<br><br>

    <strong>Appointment Details</strong><br>
    <strong>Reference:</strong> {{ $reference }}<br>
    <strong>Pet type:</strong> {{ $pet_type }}<br>
    <strong>Service type:</strong> {{ $service_provider_category }}<br>
    <strong>Appointment note:</strong> {{ $appointment_note }}<br>
    <strong>Appointment time:</strong> {{ $appointment_time }}<br>
</p>
<p>
    <i>info@petme.tech</i>
</p>

