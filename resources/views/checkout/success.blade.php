@extends('layouts.app')

@section('title', 'Thank You — Hillnest')

@section('content')
<section class="checkout-success-page">
    <div class="checkout-success-hero">
        <div class="checkout-shell checkout-success-hero__inner">
            <div class="checkout-success__icon" aria-hidden="true">
                <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20 6 9 17l-5-5"/>
                </svg>
            </div>
            <p class="checkout-eyebrow">Order confirmed</p>
            <h1>Thank You!</h1>
            <p class="checkout-success-hero__lead">
                Your order is confirmed. We are preparing your HillNest ghee with care and will ship it to you soon.
            </p>
            <span class="checkout-success-order-pill">Order {{ $order->order_number }}</span>
        </div>
    </div>

    <div class="checkout-shell checkout-success-body">
        <div class="checkout-success-layout">
            <article class="checkout-success-card">
                <header class="checkout-success-card__head">
                    <p class="checkout-eyebrow">Your items</p>
                    <h2>What you ordered</h2>
                </header>

                <ul class="checkout-success-items">
                    @foreach($order->items as $item)
                    <li>
                        <div class="checkout-success-item__meta">
                            <span class="checkout-success-item__name">{{ $item->product_name }}</span>
                            @if($item->product_size)
                            <span class="checkout-success-item__size">{{ $item->product_size }}</span>
                            @endif
                            <span class="checkout-success-item__qty">Qty {{ $item->quantity }}</span>
                        </div>
                        <strong class="checkout-success-item__price">₹{{ number_format($item->line_total, 0) }}</strong>
                    </li>
                    @endforeach
                </ul>

                <div class="checkout-success-shipping">
                    <p class="checkout-eyebrow">Delivery to</p>
                    <p class="checkout-success-shipping__name">{{ $order->customer_name }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->city }}, {{ $order->state }} — {{ $order->pincode }}</p>
                    <p class="checkout-success-shipping__phone">{{ $order->customer_phone }}</p>
                </div>
            </article>

            <aside class="checkout-success-summary" aria-label="Order summary">
                <header class="checkout-success-summary__head">
                    <p class="checkout-eyebrow">Summary</p>
                    <h2>Order total</h2>
                </header>

                <dl class="checkout-success-totals">
                    <div>
                        <dt>Subtotal</dt>
                        <dd>₹{{ number_format($order->subtotal, 0) }}</dd>
                    </div>
                    @if($order->hasCoupon())
                    <div class="checkout-success-totals__discount">
                        <dt>Coupon ({{ $order->coupon_code }})</dt>
                        <dd>-₹{{ number_format($order->discount_amount, 0) }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt>Shipping</dt>
                        <dd>{{ $order->shipping_fee > 0 ? '₹'.number_format($order->shipping_fee, 0) : 'FREE' }}</dd>
                    </div>
                    <div class="checkout-success-totals__grand">
                        <dt>Total paid</dt>
                        <dd>₹{{ number_format($order->total, 0) }}</dd>
                    </div>
                </dl>

                <div class="checkout-success-summary__status">
                    <span class="checkout-success__label">Status</span>
                    <span class="checkout-success__status checkout-success__status--{{ $order->status }}">{{ $order->status_label }}</span>
                </div>

                <div class="checkout-success__actions">
                    <a href="{{ route('shop.index') }}" class="btn-primary checkout-success-shop-btn">
                        <span>Shop More</span>
                    </a>
                    <a href="{{ route('account.orders.show', $order->order_number) }}" class="checkout-success-secondary">
                        View order details
                    </a>
                </div>

                <p class="checkout-success__hint">
                    You can track this order anytime from your account.
                </p>
            </aside>
        </div>
    </div>
</section>
@endsection
