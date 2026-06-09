@extends('layouts.app')

@section('title', 'My Profile — Hillnest')

@section('content')
<section class="account-profile-page">
    <div class="account-profile-shell">
        <div class="account-profile-card">
            <div class="account-profile-card__header">
                <div>
                    <p class="account-eyebrow">Account</p>
                    <h1>My Profile</h1>
                    <p>Keep your HillNest contact details updated for smoother deliveries and order updates.</p>
                </div>
                <a href="{{ route('account.orders') }}" class="account-profile-link">View Orders</a>
            </div>

            <form method="POST" action="{{ route('account.profile.update') }}" class="account-profile-form">
                @csrf
                @method('PATCH')

                <div class="account-field">
                    <label for="profile-name">Name</label>
                    <input id="profile-name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <p class="account-field__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="account-field">
                    <label for="profile-email">Email</label>
                    <input id="profile-email" type="email" value="{{ $user->email }}" disabled>
                </div>

                <div class="account-field">
                    <label for="profile-phone">Phone</label>
                    <input id="profile-phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Add your phone number">
                    @error('phone')
                        <p class="account-field__error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary account-profile-submit"><span>Save Changes</span></button>
            </form>
        </div>
    </div>
</section>
@endsection
