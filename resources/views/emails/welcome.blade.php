@component('mail::message')
# Hello {{ $user->name }}

Thank you for creating account with us. We have sent a verification link on your registered email.
Please take a moment to verify yourself!

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Verify
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
