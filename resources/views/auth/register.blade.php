@extends('layouts.app')

@section('title', 'Register — Hillnest')

@section('content')
<section class="py-14 md:py-20 bg-cream">
    <div class="mx-auto max-w-md px-4">
        <div class="bg-white border border-hill-200 p-8 md:p-10 shadow-sm">
            <h1 class="font-display text-3xl font-semibold text-brand text-center">Join Hillnest</h1>
            <p class="mt-3 text-center text-base text-brand-light">Create an account to track orders</p>

            <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
                @csrf
                <div>
                    <label class="block text-base font-medium text-brand mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full border-2 border-hill-200 px-4 py-3 text-base focus:border-gold outline-none">
                    @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-base font-medium text-brand mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border-2 border-hill-200 px-4 py-3 text-base focus:border-gold outline-none">
                    @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-base font-medium text-brand mb-2">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border-2 border-hill-200 px-4 py-3 text-base focus:border-gold outline-none">
                </div>
                <div>
                    <label class="block text-base font-medium text-brand mb-2">Password</label>
                    <input type="password" name="password" required class="w-full border-2 border-hill-200 px-4 py-3 text-base focus:border-gold outline-none">
                    @error('password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-base font-medium text-brand mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full border-2 border-hill-200 px-4 py-3 text-base focus:border-gold outline-none">
                </div>
                <button type="submit" class="w-full btn-gold">Create Account</button>
            </form>
            <p class="mt-8 text-center text-base text-brand-light">
                Have an account? <a href="{{ route('login') }}" class="font-semibold text-gold hover:underline">Log in</a>
            </p>
        </div>
    </div>
</section>
@endsection
