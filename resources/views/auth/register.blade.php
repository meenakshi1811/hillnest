@extends('layouts.app')

@section('title', 'Register — Hillnest')

@section('content')
<section class="auth-page" aria-labelledby="register-title">
    <div class="auth-shell">
        <div class="auth-card auth-card--register">
            <aside class="auth-story auth-story--register" aria-label="Hillnest membership benefits">
                <div class="auth-story__badge">Start your Hillnest journey</div>
                <h2>Create a warmer way to shop pure ghee.</h2>
                <p>Save your details once, track every order, and make it simple to bring Himalayan bilona ghee back to your table.</p>
                <ul class="auth-benefits" aria-label="Membership benefits">
                    <li>
                        <span>01</span>
                        <strong>Quick checkout</strong>
                    </li>
                    <li>
                        <span>02</span>
                        <strong>Order history</strong>
                    </li>
                    <li>
                        <span>03</span>
                        <strong>Fresh updates</strong>
                    </li>
                </ul>
            </aside>

            <div class="auth-panel">
                <div class="auth-panel__header">
                    <p class="auth-eyebrow">Create account</p>
                    <h1 id="register-title">Join Hillnest</h1>
                    <p>Set up your account to track shipments, save contact details, and reorder your favourites without starting over.</p>
                </div>

                @include('auth.partials.google-button')
                <div class="auth-divider" role="separator"><span>or</span></div>

                <form method="POST" action="{{ route('register') }}" class="auth-form auth-form--register" data-register-form novalidate>
                    @csrf

                    <div class="auth-field auth-field--full">
                        <label for="name">Full name</label>
                        <div class="auth-input-wrap">
                            <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Your full name" @class(['is-invalid' => $errors->has('name')])>
                        </div>
                        <p class="auth-error" data-field-error="name" @if(!$errors->has('name')) hidden @endif>{{ $errors->first('name') }}</p>
                    </div>

                    <div class="auth-field auth-field--full">
                        <label for="email">Email address</label>
                        <div class="auth-input-wrap">
                            <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/><path d="m22 6-10 7L2 6"/></svg>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@example.com" @class(['is-invalid' => $errors->has('email')])>
                        </div>
                        <p class="auth-error" data-field-error="email" @if(!$errors->has('email')) hidden @endif>{{ $errors->first('email') }}</p>
                    </div>

                    <div class="auth-field">
                        <label for="password">Password</label>
                        <div class="auth-input-wrap">
                            <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>
                            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Create a password" @class(['is-invalid' => $errors->has('password')])>
                        </div>
                        <p class="auth-error" data-field-error="password" @if(!$errors->has('password')) hidden @endif>{{ $errors->first('password') }}</p>
                    </div>

                    <div class="auth-field">
                        <label for="password_confirmation">Confirm password</label>
                        <div class="auth-input-wrap">
                            <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="m20 6-11 11-5-5"/><path d="M21 12a9 9 0 1 1-5.25-8.18"/></svg>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat your password" @class(['is-invalid' => $errors->has('password_confirmation')])>
                        </div>
                        <p class="auth-error" data-field-error="password_confirmation" @if(!$errors->has('password_confirmation')) hidden @endif>{{ $errors->first('password_confirmation') }}</p>
                    </div>

                    <button type="submit" class="auth-submit auth-submit--gold auth-field--full" data-register-submit>
                        <span class="auth-submit__loader" hidden aria-hidden="true"></span>
                        <span class="auth-submit__text">Create account</span>
                        <svg class="auth-submit__icon" aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </button>
                </form>

                <div class="auth-switch">
                    <span>Already have an account?</span>
                    <a href="{{ route('login') }}">Log in</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/auth.js') }}"></script>
@endpush
