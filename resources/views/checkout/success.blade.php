@extends('layouts.app')

@section('title', 'Order Confirmed — Hillnest')

@section('content')
<section class="py-16 md:py-24 bg-cream">
    <div class="mx-auto max-w-lg px-4 text-center">
        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100 text-4xl text-emerald-700">✓</div>
        <h1 class="font-display mt-8 text-3xl md:text-4xl font-semibold text-brand">Thank You!</h1>
        <p class="mt-4 text-lg text-brand-light">Your order has been placed. We'll prepare your Hillnest ghee with care.</p>
        <div class="mt-10 bg-white border border-hill-200 p-8 text-left">
            <p class="text-sm font-medium uppercase tracking-wider text-gold">Order Number</p>
            <p class="text-2xl font-bold text-brand mt-1">{{ $order->order_number }}</p>
            <p class="mt-6 text-sm font-medium uppercase tracking-wider text-gold">Total (COD)</p>
            <p class="text-3xl font-bold text-brand mt-1">₹{{ number_format($order->total, 0) }}</p>
            <p class="mt-4 text-base text-brand-light">Status: <span class="font-semibold {{ $order->status_badge_classes }} px-2 py-0.5 rounded">{{ $order->status_label }}</span></p>
        </div>
        @auth
            <a href="{{ route('account.orders.show', $order->order_number) }}" class="mt-8 inline-block btn-primary">View Order</a>
        @else
            <a href="{{ route('register') }}" class="mt-6 block text-base text-gold font-semibold hover:underline">Create account to track this order</a>
        @endauth
        <a href="{{ route('shop.index') }}" class="mt-4 block text-base text-brand-light hover:text-gold">Continue Shopping</a>
    </div>
</section>
@endsection
