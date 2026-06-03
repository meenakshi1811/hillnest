@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4">
    <h1 class="font-display text-2xl font-bold text-stone-800">Orders</h1>
    <form method="GET" class="flex flex-wrap gap-2">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search orders..." class="rounded-xl border border-stone-200 px-4 py-2 text-sm w-48 focus:border-hill-500 outline-none">
        <select name="status" onchange="this.form.submit()" class="rounded-xl border border-stone-200 px-3 py-2 text-sm">
            <option value="">All statuses</option>
            @foreach(\App\Models\Order::STATUSES as $key => $label)
                <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </form>
</div>

<div class="mt-8 overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-stone-50 text-left text-xs uppercase tracking-wider text-stone-500">
            <tr>
                <th class="px-4 py-3">Order</th>
                <th class="px-4 py-3 hidden sm:table-cell">Customer</th>
                <th class="px-4 py-3">Total</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 hidden md:table-cell">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @forelse($orders as $order)
            <tr class="hover:bg-stone-50">
                <td class="px-4 py-3"><a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-forest-700 hover:underline">{{ $order->order_number }}</a></td>
                <td class="px-4 py-3 hidden sm:table-cell text-stone-600">{{ $order->customer_name }}</td>
                <td class="px-4 py-3 font-semibold">₹{{ number_format($order->total, 0) }}</td>
                <td class="px-4 py-3"><span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $order->status_badge_classes }}">{{ $order->status_label }}</span></td>
                <td class="px-4 py-3 hidden md:table-cell text-stone-500">{{ $order->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-12 text-center text-stone-400">No orders found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $orders->links() }}</div>
@endsection
