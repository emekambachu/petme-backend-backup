<img src="{{ @config('app.url').'logo.png' }}" width="100"/><br>

<p>
    Hello {{ $name }}<br>
    Your appointment has been completed by {{ $service_provider_name }}.<br><br>

    If you have any complaints about your appointment, please reach out to the service provider via the chat or contact us via our email below.<br><br>

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

