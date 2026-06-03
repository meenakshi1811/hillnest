@extends('layouts.app')

@section('title', 'Log in — Hillnest')

@section('content')
<section class="py-14 md:py-20 bg-cream">
    <div class="mx-auto max-w-md px-4">
        <div class="bg-white border border-hill-200 p-8 md:p-10 shadow-sm">
            <h1 class="font-display text-3xl font-semibold text-brand text-center">Welcome Back</h1>
            <p class="mt-3 text-center text-base text-brand-light">Log in to track your orders</p>

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                @csrf
                <div>
                    <label class="block text-base font-medium text-brand mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full border-2 border-hill-200 px-4 py-3 text-base focus:border-gold outline-none">
                    @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-base font-medium text-brand mb-2">Password</label>
                    <input type="password" name="password" required class="w-full border-2 border-hill-200 px-4 py-3 text-base focus:border-gold outline-none">
                </div>
                <label class="flex items-center gap-2 text-base text-brand-light">
                    <input type="checkbox" name="remember" class="rounded border-hill-300 text-gold">
                    Remember me
                </label>
                <button type="submit" class="w-full btn-primary">Log in</button>
            </form>
            <p class="mt-8 text-center text-base text-brand-light">
                New here? <a href="{{ route('register') }}" class="font-semibold text-gold hover:underline">Register</a>
            </p>
            <p class="mt-4 text-center">
                <a href="{{ route('login', ['redirect' => 'admin']) }}" class="text-sm text-brand-light hover:text-gold">Admin login →</a>
            </p>
        </div>
    </div>
</section>
@endsection
