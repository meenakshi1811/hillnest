@extends('layouts.app')

@section('title', 'Reset password — Hillnest')

@section('content')
<section class="auth-page" aria-labelledby="reset-password-title">
    <div class="auth-shell">
        <div class="auth-card">
            <aside class="auth-story" aria-label="Create a new password">
                <div class="auth-story__badge">New password</div>
                <h2>Choose a strong password.</h2>
                <p>Pick something unique that you have not used elsewhere. You will be able to sign in right away once your password is updated.</p>
                <ul class="auth-benefits" aria-label="Password tips">
                    <li>
                        <span>01</span>
                        <strong>At least 8 characters</strong>
                    </li>
                    <li>
                        <span>02</span>
                        <strong>Mix letters and numbers</strong>
                    </li>
                    <li>
                        <span>03</span>
                        <strong>Keep it private</strong>
                    </li>
                </ul>
            </aside>

            <div class="auth-panel">
                <div class="auth-panel__header">
                    <p class="auth-eyebrow">Reset password</p>
                    <h1 id="reset-password-title">Set a new password</h1>
                    <p>Enter your new password below to finish resetting your account.</p>
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="auth-form">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="auth-field">
                        <label for="email">Email address</label>
                        <div class="auth-input-wrap">
                            <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/><path d="m22 6-10 7L2 6"/></svg>
                            <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autofocus autocomplete="email" placeholder="you@example.com" @class(['is-invalid' => $errors->has('email')])>
                        </div>
                        @error('email')<p class="auth-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="auth-field">
                        <label for="password">New password</label>
                        <div class="auth-input-wrap">
                            <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>
                            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Create a new password" @class(['is-invalid' => $errors->has('password')])>
                        </div>
                        @error('password')<p class="auth-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="auth-field">
                        <label for="password_confirmation">Confirm password</label>
                        <div class="auth-input-wrap">
                            <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="m20 6-11 11-5-5"/><path d="M21 12a9 9 0 1 1-5.25-8.18"/></svg>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat your new password" @class(['is-invalid' => $errors->has('password_confirmation')])>
                        </div>
                        @error('password_confirmation')<p class="auth-error">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="auth-submit">
                        <span>Update password</span>
                        <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </button>
                </form>

                <div class="auth-switch">
                    <span>Back to</span>
                    <a href="{{ route('login') }}">Log in</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
