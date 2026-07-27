@extends('layouts.app')

@section('title', 'Forgot password — Hillnest')

@section('content')
<section class="auth-page" aria-labelledby="forgot-password-title">
    <div class="auth-shell">
        <div class="auth-card">
            <aside class="auth-story" aria-label="Password recovery">
                <div class="auth-story__badge">Account recovery</div>
                <h2>We will help you get back in.</h2>
                <p>Enter the email linked to your Hillnest account and we will send you a secure link to reset your password.</p>
                <ul class="auth-benefits" aria-label="Security notes">
                    <li>
                        <span>01</span>
                        <strong>Secure reset link</strong>
                    </li>
                    <li>
                        <span>02</span>
                        <strong>Expires in 60 minutes</strong>
                    </li>
                    <li>
                        <span>03</span>
                        <strong>One-time use</strong>
                    </li>
                </ul>
            </aside>

            <div class="auth-panel">
                <div class="auth-panel__header">
                    <p class="auth-eyebrow">Forgot password</p>
                    <h1 id="forgot-password-title">Reset your password</h1>
                    <p>We will email you a link to choose a new password for your account.</p>
                </div>

                @if (session('status'))
                    <p class="auth-status" role="status">{{ session('status') }}</p>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                    @csrf

                    <div class="auth-field">
                        <label for="email">Email address</label>
                        <div class="auth-input-wrap">
                            <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/><path d="m22 6-10 7L2 6"/></svg>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="you@example.com" @class(['is-invalid' => $errors->has('email')])>
                        </div>
                        @error('email')<p class="auth-error">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="auth-submit">
                        <span>Send reset link</span>
                        <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </button>
                </form>

                <div class="auth-switch">
                    <span>Remember your password?</span>
                    <a href="{{ route('login') }}">Back to login</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
