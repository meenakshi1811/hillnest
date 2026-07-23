@extends('layouts.app')

@section('title', 'Order Confirmed — Hillnest')

@section('content')
<section class="checkout-success">
    <div class="checkout-shell checkout-success__inner">
        <div class="checkout-success__icon" aria-hidden="true">✓</div>
        <p class="checkout-eyebrow">Order confirmed</p>
        <h1>Thank You!</h1>
        <p class="checkout-success__lead">Your order has been placed. We'll prepare your HillNest ghee with care and get it on its way soon.</p>

        <div class="checkout-success__card">
            <div class="checkout-success__detail">
                <span class="checkout-success__label">Order Number</span>
                <strong>{{ $order->order_number }}</strong>
            </div>
            <div class="checkout-success__detail">
                <span class="checkout-success__label">Total</span>
                <strong class="checkout-success__total">₹{{ number_format($order->total, 0) }}</strong>
            </div>
            <div class="checkout-success__detail checkout-success__detail--status">
                <span class="checkout-success__label">Status</span>
                <span class="checkout-success__status checkout-success__status--{{ $order->status }}">{{ $order->status_label }}</span>
            </div>
        </div>

        <div class="checkout-success__actions">
            <a href="{{ route('account.orders.show', $order->order_number) }}" class="btn-primary"><span>View Order</span></a>
            <a href="{{ route('shop.index') }}" class="checkout-success__continue">Continue Shopping</a>
        </div>
    </div>
</section>
@endsection
