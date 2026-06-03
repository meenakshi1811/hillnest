@extends('layouts.admin')

@section('title', $user->name)

@section('content')
<a href="{{ route('admin.users.index') }}" class="text-sm text-forest-700 hover:underline">← Customers</a>
<h1 class="font-display mt-2 text-2xl font-bold text-stone-800">{{ $user->name }}</h1>
<p class="text-sm text-stone-500">{{ $user->email }} @if($user->phone)· {{ $user->phone }}@endif</p>

<div class="mt-8 rounded-2xl border border-stone-200 bg-white shadow-sm overflow-hidden">
    <div class="border-b border-stone-100 px-6 py-4 font-semibold text-stone-800">Order History</div>
    @forelse($orders as $order)
    <a href="{{ route('admin.orders.show', $order) }}" class="flex items-center justify-between px-6 py-4 border-b border-stone-50 hover:bg-stone-50 last:border-0">
        <div>
            <p class="font-medium text-forest-700">{{ $order->order_number }}</p>
            <p class="text-xs text-stone-500">{{ $order->created_at->format('d M Y') }}</p>
        </div>
        <div class="text-right">
            <p class="font-semibold">₹{{ number_format($order->total, 0) }}</p>
            <span class="text-xs {{ $order->status_badge_classes }} rounded-full px-2 py-0.5">{{ $order->status_label }}</span>
        </div>
    </a>
    @empty
    <p class="px-6 py-12 text-center text-stone-400 text-sm">No orders from this customer.</p>
    @endforelse
</div>
@if($orders->hasPages())<div class="mt-4">{{ $orders->links() }}</div>@endif
@endsection
