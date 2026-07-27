@extends('emails.layouts.hillnest')

@section('content')
    @include('emails.partials.eyebrow', ['label' => 'Password reset'])

    @include('emails.partials.heading', ['text' => 'Reset your password'])

    @include('emails.partials.lede', ['text' => 'Hi '.e($user->name).', we received a request to reset the password for your Hillnest account. Click the button below to choose a new password.'])

    @include('emails.partials.button', [
        'url' => $resetUrl,
        'label' => 'Reset password',
    ])

    @include('emails.partials.note', ['text' => 'This link expires in 60 minutes. If you did not request a password reset, you can safely ignore this email.'])
@endsection
