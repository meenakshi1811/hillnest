@extends('layouts.app')

@section('title', 'Log in — Hillnest')

@section('content')
@php
    $isAdminRedirect = request('redirect') === 'admin';
@endphp

<section class="auth-page" aria-labelledby="login-title">
    <div class="auth-shell">
        <div class="auth-card">
            <aside class="auth-story" aria-label="Hillnest account benefits">
                <div class="auth-story__badge">Pure A2 Bilona Ghee</div>
                <h2>Welcome back to your Himalayan pantry.</h2>
                <p>Sign in to follow every jar from the hills to your home, review past orders, and reorder your favourites faster.</p>
                <ul class="auth-benefits" aria-label="Account benefits">
                    <li>
                        <span>01</span>
                        <strong>Track orders</strong>
                    </li>
                    <li>
                        <span>02</span>
                        <strong>Reorder with ease</strong>
                    </li>
                    <li>
                        <span>03</span>
                        <strong>Secure checkout</strong>
                    </li>
                </ul>
            </aside>

            <div class="auth-panel">
                <div class="auth-panel__header">
                    <p class="auth-eyebrow">{{ $isAdminRedirect ? 'Admin access' : 'Customer login' }}</p>
                    <h1 id="login-title">{{ $isAdminRedirect ? 'Log in to manage Hillnest' : 'Log in to Hillnest' }}</h1>
                    <p>{{ $isAdminRedirect ? 'Use your admin credentials to continue to the dashboard.' : 'Access your orders, saved details, and account updates.' }}</p>
                </div>

                @if (session('status'))
                    <p class="auth-status" role="status">{{ session('status') }}</p>
                @endif

                <form method="POST" action="{{ route('login') }}" class="auth-form">
                    @csrf
                    @if($isAdminRedirect)
                        <input type="hidden" name="redirect" value="admin">
                    @endif

                    <div class="auth-field">
                        <label for="email">Email address</label>
                        <div class="auth-input-wrap">
                            <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/><path d="m22 6-10 7L2 6"/></svg>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="you@example.com" @class(['is-invalid' => $errors->has('email')])>
                        </div>
                        @error('email')<p class="auth-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="auth-field">
                        <label for="password">Password</label>
                        <div class="auth-input-wrap">
                            <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>
                            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" @class(['is-invalid' => $errors->has('password')])>
                        </div>
                        @error('password')<p class="auth-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="auth-options">
                        <label class="auth-remember" for="remember">
                            <input id="remember" type="checkbox" name="remember" @checked(old('remember'))>
                            <span>Remember me</span>
                        </label>
                        @unless($isAdminRedirect)
                            <a href="{{ route('password.request') }}">Forgot password?</a>
                        @endunless
                    </div>

                    <button type="submit" class="auth-submit">
                        <span>{{ $isAdminRedirect ? 'Open dashboard' : 'Log in' }}</span>
                        <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </button>
                </form>

                <div class="auth-switch">
                    <span>New to Hillnest?</span>
                    <a href="{{ route('register') }}">Create an account</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
