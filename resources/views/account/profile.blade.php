@extends('layouts.app')

@section('title', 'My Profile — Hillnest')

@section('content')
<section class="py-12">
    <div class="mx-auto max-w-md px-4 sm:px-6">
        <h1 class="font-display text-3xl font-bold text-forest-800">My Profile</h1>
        <form method="POST" action="{{ route('account.profile.update') }}" class="mt-8 rounded-2xl border border-hill-200 bg-white p-6 shadow-sm space-y-5">
            @csrf @method('PATCH')
            <div>
                <label class="block text-sm font-medium text-stone-600 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border border-hill-200 px-4 py-2.5 focus:border-hill-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-600 mb-1">Email</label>
                <input type="email" value="{{ $user->email }}" disabled class="w-full rounded-xl border border-hill-100 bg-hill-50 px-4 py-2.5 text-stone-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-600 mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-xl border border-hill-200 px-4 py-2.5 focus:border-hill-500 outline-none">
            </div>
            <button type="submit" class="w-full rounded-full bg-forest-700 py-3 font-semibold text-white hover:bg-forest-800 transition">Save Changes</button>
        </form>
    </div>
</section>
@endsection
