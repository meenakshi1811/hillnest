@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4">
    <h1 class="font-display text-2xl font-bold text-stone-800">Customers</h1>
    <form method="GET">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search customers..." class="rounded-xl border border-stone-200 px-4 py-2 text-sm w-56 focus:border-hill-500 outline-none">
    </form>
</div>

<div class="mt-8 overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-stone-50 text-left text-xs uppercase tracking-wider text-stone-500">
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3 hidden sm:table-cell">Email</th>
                <th class="px-4 py-3">Orders</th>
                <th class="px-4 py-3 hidden md:table-cell">Joined</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @forelse($users as $user)
            <tr class="hover:bg-stone-50">
                <td class="px-4 py-3"><a href="{{ route('admin.users.show', $user) }}" class="font-medium text-forest-700 hover:underline">{{ $user->name }}</a></td>
                <td class="px-4 py-3 hidden sm:table-cell text-stone-600">{{ $user->email }}</td>
                <td class="px-4 py-3">{{ $user->orders_count }}</td>
                <td class="px-4 py-3 hidden md:table-cell text-stone-500">{{ $user->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-12 text-center text-stone-400">No customers yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $users->links() }}</div>
@endsection
