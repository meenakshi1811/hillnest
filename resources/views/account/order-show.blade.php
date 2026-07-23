@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' — Hillnest')

@section('content')
<section class="py-10 md:py-14 bg-cream">
    <div class="mx-auto max-w-3xl px-4 sm:px-6">
        <a href="{{ route('account.orders') }}" class="text-base font-semibold text-gold hover:underline">← Back to orders</a>
        <h1 class="font-display mt-4 text-3xl font-semibold text-brand">Order {{ $order->order_number }}</h1>
        <p class="text-base text-brand-light mt-2">{{ $order->created_at->format('d M Y, h:i A') }}</p>

        <div class="mt-8 bg-white border border-hill-200 p-6 md:p-8">
            <div class="flex justify-between items-center">
                <span class="text-base text-brand-light">Status</span>
                <span class="status-badge {{ $order->status_badge_classes }}">{{ $order->status_label }}</span>
            </div>
            <ul class="mt-6 divide-y divide-hill-200">
                @foreach($order->items as $item)
                <li class="py-4 flex justify-between gap-4">
                    <div>
                        <p class="text-lg font-semibold text-brand">{{ $item->product_name }}</p>
                        @if($item->product_size)<p class="text-base text-brand-light">{{ $item->product_size }}</p>@endif
                        <p class="text-sm text-brand-light mt-1">Qty: {{ $item->quantity }}</p>
                    </div>
                    <p class="text-lg font-bold">₹{{ number_format($item->line_total, 0) }}</p>
                </li>
                @endforeach
            </ul>
            <dl class="mt-6 border-t-2 border-hill-200 pt-5 space-y-2 text-base">
                <div class="flex justify-between"><dt>Subtotal</dt><dd>₹{{ number_format($order->subtotal, 0) }}</dd></div>
                <div class="flex justify-between"><dt>Shipping</dt><dd>₹{{ number_format($order->shipping_fee, 0) }}</dd></div>
                <div class="flex justify-between text-xl font-bold text-brand pt-2"><dt>Total</dt><dd>₹{{ number_format($order->total, 0) }}</dd></div>
            </dl>
        </div>

        <div class="mt-6 bg-white border border-hill-200 p-6 text-base text-brand-light">
            <p class="font-bold text-brand text-lg mb-3">Delivery Address</p>
            <p>{{ $order->customer_name }}</p>
            <p>{{ $order->shipping_address }}</p>
            <p>{{ $order->city }}, {{ $order->state }} — {{ $order->pincode }}</p>
            <p class="mt-2">Phone: {{ $order->customer_phone }}</p>
        </div>
    </div>
</section>
@endsection
