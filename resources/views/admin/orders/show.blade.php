@extends('layouts.admin')

@section('title', 'Order ' . $order->order_number)

@section('content')
<a href="{{ route('admin.orders.index') }}" class="text-sm text-forest-700 hover:underline">← Orders</a>
<h1 class="font-display mt-2 text-2xl font-bold text-stone-800">{{ $order->order_number }}</h1>

<div class="mt-8 grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-6">
        <div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
            <h2 class="font-semibold text-stone-800">Items</h2>
            <ul class="mt-4 divide-y divide-stone-100">
                @foreach($order->items as $item)
                <li class="py-3 flex justify-between">
                    <span>{{ $item->product_name }} @if($item->product_size)({{ $item->product_size }})@endif × {{ $item->quantity }}</span>
                    <span class="font-medium">₹{{ number_format($item->line_total, 0) }}</span>
                </li>
                @endforeach
            </ul>
            <dl class="mt-4 pt-4 border-t text-sm space-y-1">
                <div class="flex justify-between"><dt>Subtotal</dt><dd>₹{{ number_format($order->subtotal, 0) }}</dd></div>
                <div class="flex justify-between"><dt>Shipping</dt><dd>₹{{ number_format($order->shipping_fee, 0) }}</dd></div>
                <div class="flex justify-between font-bold text-base"><dt>Total</dt><dd>₹{{ number_format($order->total, 0) }}</dd></div>
            </dl>
        </div>
        <div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm text-sm text-stone-600">
            <h2 class="font-semibold text-stone-800 mb-3">Customer & Shipping</h2>
            <p><strong>{{ $order->customer_name }}</strong></p>
            <p>{{ $order->customer_email }} · {{ $order->customer_phone }}</p>
            <p class="mt-2">{{ $order->shipping_address }}</p>
            <p>{{ $order->city }}, {{ $order->state }} — {{ $order->pincode }}</p>
            @if($order->notes)<p class="mt-2 text-stone-500">Notes: {{ $order->notes }}</p>@endif
        </div>
    </div>

    <div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm h-fit">
        <h2 class="font-semibold text-stone-800">Update Status</h2>
        <p class="mt-2 text-sm">Current: <span class="{{ $order->status_badge_classes }} rounded-full px-2 py-0.5 text-xs font-semibold">{{ $order->status_label }}</span></p>
        <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-4 space-y-3">
            @csrf @method('PATCH')
            <select name="status" class="w-full rounded-xl border border-stone-200 px-3 py-2 text-sm">
                @foreach(\App\Models\Order::STATUSES as $key => $label)
                    <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="w-full rounded-xl bg-forest-700 py-2.5 text-sm font-semibold text-white hover:bg-forest-800">Update Status</button>
        </form>
        <p class="mt-4 text-xs text-stone-400">Payment: {{ strtoupper($order->payment_method) }}</p>
        <p class="text-xs text-stone-400">Placed: {{ $order->created_at->format('d M Y H:i') }}</p>
        @if($order->user)
        <a href="{{ route('admin.users.show', $order->user) }}" class="mt-4 inline-block text-sm text-forest-700 hover:underline">View customer profile →</a>
        @endif
    </div>
</div>
@endsection
