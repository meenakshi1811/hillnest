@extends('layouts.app')

@section('title', 'My Profile — Hillnest')

@section('content')
<section class="account-page">
    <div class="account-page-hero">
        <div class="account-page-shell">
            <nav class="account-page-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('account.orders') }}">My Account</a>
                <span aria-hidden="true">/</span>
                <span>Profile</span>
            </nav>
            <div class="account-page-header">
                <p class="account-page-eyebrow">Your HillNest Account</p>
                <h1>My Profile</h1>
                <p>Keep your contact details fresh so every delivery from the mountains reaches you smoothly.</p>
            </div>
        </div>
    </div>

    <div class="account-page-shell account-page-body">
        <div class="account-layout">
            @include('account.partials.sidebar', ['active' => 'profile'])

            <main class="account-main">
                <div class="account-main__head">
                    <div>
                        <p class="account-page-eyebrow">Profile settings</p>
                        <h2>Personal details</h2>
                    </div>
                    <a href="{{ route('account.orders') }}" class="account-main__shop-link">View orders</a>
                </div>

                <div class="account-profile-note">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                    <p>Use your email to sign in. Phone is optional and helps with delivery updates.</p>
                </div>

                <form method="POST" action="{{ route('account.profile.update') }}" class="account-profile-form" data-profile-form novalidate>
                    @csrf
                    @method('PATCH')

                    <div class="account-profile-grid">
                        <div class="account-field account-field--full">
                            <label for="profile-name">Full name</label>
                            <div class="account-field__wrap">
                                <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                <input id="profile-name" type="text" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" placeholder="Your full name" @class(['is-invalid' => $errors->has('name')])>
                            </div>
                            <p class="account-field__error" data-field-error="name" @if(!$errors->has('name')) hidden @endif>{{ $errors->first('name') }}</p>
                        </div>

                        <div class="account-field">
                            <label for="profile-email">Email address</label>
                            <div class="account-field__wrap @if($user->email) account-field__wrap--disabled @endif">
                                <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/><path d="m22 6-10 7L2 6"/></svg>
                                @if($user->email)
                                    <input id="profile-email" type="email" value="{{ $user->email }}" disabled>
                                @else
                                    <input id="profile-email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="Add your email address" @class(['is-invalid' => $errors->has('email')])>
                                @endif
                            </div>
                            <p class="account-field__hint">{{ $user->email ? 'Used for login and order confirmations' : 'Required for login, order confirmations, and password recovery' }}</p>
                            <p class="account-field__error" data-field-error="email" @if(!$errors->has('email')) hidden @endif>{{ $errors->first('email') }}</p>
                        </div>

                        <div class="account-field">
                            <label for="profile-phone">Phone number</label>
                            <div class="account-field__wrap">
                                <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.35 1.9.66 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.23a2 2 0 0 1 2.11-.45c.91.31 1.85.53 2.81.66A2 2 0 0 1 22 16.92z"/></svg>
                                <input id="profile-phone" type="tel" name="phone" value="{{ old('phone', $user->phone) }}" autocomplete="tel" placeholder="Your phone number" @class(['is-invalid' => $errors->has('phone')])>
                            </div>
                            <p class="account-field__error" data-field-error="phone" @if(!$errors->has('phone')) hidden @endif>{{ $errors->first('phone') }}</p>
                        </div>
                    </div>

                    <div class="account-profile-meta">
                        <span>Member since {{ $user->created_at->format('F Y') }}</span>
                    </div>

                    <button type="submit" class="btn-primary account-profile-submit" data-profile-submit>
                        <span class="auth-submit__loader" hidden aria-hidden="true"></span>
                        <span class="auth-submit__text">Save Changes</span>
                    </button>
                </form>
            </main>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/auth.js') }}"></script>
@endpush
