<img src="{{ asset('/images/logo.svg') }}" width="100"/><br>

<p>
    Hello {{ $name }}<br>
    Thank you for signing up on our platform.
    To Verify your email and start using this account, click on the link below<br>
    <a href="{{ route('user.verify.token', $verification_token)}}">
        Click to verify
    </a>
</p>
