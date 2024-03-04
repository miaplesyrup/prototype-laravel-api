<!-- resources/views/confirmation.blade.php -->
{{-- <p>Please confirm your email by clicking the following link:</p>
<a href="{{ $confirmationUrl }}">Confirm Email</a> --}}


<!-- resources/views/confirmation.blade.php -->
<p>Please confirm your email by clicking the following link:</p>
<a href="{{ url("http://localhost:3000/email-verification/{$user->id}/{$user->confirmation_token}") }}">Confirm Email</a>
