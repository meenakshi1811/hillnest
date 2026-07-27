@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' — Hillnest')

@section('content')
<section class="account-page">
    <div class="account-page-hero">
        <div class="account-page-shell">
            <nav class="account-page-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('account.orders') }}">My Orders</a>
                <span aria-hidden="true">/</span>
                <span>{{ $order->order_number }}</span>
            </nav>
            <div class="account-page-header">
                <p class="account-page-eyebrow">Order details</p>
                <h1>{{ $order->order_number }}</h1>
                <p>Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>
    </div>

    <div class="account-page-shell account-page-body">
        <div class="account-layout">
            @include('account.partials.sidebar', ['active' => 'orders'])

            <main class="account-main">
                <a href="{{ route('account.orders') }}" class="order-detail-back">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                    Back to orders
                </a>

                <div class="order-detail-layout">
                    <article class="order-detail-card">
                        <header class="order-detail-card__head">
                            <div>
                                <p class="account-page-eyebrow">Your items</p>
                                <h2>What you ordered</h2>
                            </div>
                            <span class="order-status order-status--{{ $order->status }}">{{ $order->status_label }}</span>
                        </header>

                        <ul class="order-detail-items">
                            @foreach($order->items as $item)
                            <li class="order-detail-item">
                                <div class="order-detail-item__thumb">
                                    @if($item->product?->image_url)
                                        <img src="{{ $item->product->image_url }}" alt="">
                                    @else
                                        <span aria-hidden="true">🫙</span>
                                    @endif
                                </div>
                                <div class="order-detail-item__meta">
                                    <span class="order-detail-item__name">{{ $item->product_name }}</span>
                                    @if($item->product_size)
                                    <span class="order-detail-item__size">{{ $item->product_size }}</span>
                                    @endif
                                    <span class="order-detail-item__qty">Qty {{ $item->quantity }}</span>
                                </div>
                                <strong class="order-detail-item__price">₹{{ number_format($item->line_total, 0) }}</strong>
                            </li>
                            @endforeach
                        </ul>

                        @if($order->isPaid() && $order->status !== 'cancelled')
                            @php
                                $reviewableItems = $order->items->filter(fn ($item) => $item->product_id);
                            @endphp
                            @if($reviewableItems->isNotEmpty())
                            <div class="order-detail-reviews" id="reviews">
                                <div class="order-detail-reviews__head">
                                    <p class="account-page-eyebrow">Your feedback</p>
                                    <h3>Rate your products</h3>
                                    <p>Share how HillNest ghee worked for your kitchen — it helps other families choose with confidence.</p>
                                </div>

                                <div class="order-detail-reviews__list">
                                    @foreach($reviewableItems as $item)
                                        @include('account.partials.review-form', ['item' => $item, 'review' => $item->review])
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @endif

                        <div class="order-detail-shipping">
                            <p class="account-page-eyebrow">Delivery to</p>
                            <p class="order-detail-shipping__name">{{ $order->customer_name }}</p>
                            <p>{{ $order->shipping_address }}</p>
                            <p>{{ $order->city }}, {{ $order->state }} — {{ $order->pincode }}</p>
                            <p class="order-detail-shipping__phone">{{ $order->customer_phone }}</p>
                        </div>
                    </article>

                    <aside class="order-detail-summary" aria-label="Order summary">
                        <header class="order-detail-summary__head">
                            <p class="account-page-eyebrow">Summary</p>
                            <h2>Order total</h2>
                        </header>

                        <dl class="order-detail-totals">
                            <div>
                                <dt>Subtotal</dt>
                                <dd>₹{{ number_format($order->subtotal, 0) }}</dd>
                            </div>
                            @if($order->hasCoupon())
                            <div class="order-detail-totals__discount">
                                <dt>Coupon ({{ $order->coupon_code }})</dt>
                                <dd>-₹{{ number_format($order->discount_amount, 0) }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt>Shipping</dt>
                                <dd>{{ $order->shipping_fee > 0 ? '₹'.number_format($order->shipping_fee, 0) : 'FREE' }}</dd>
                            </div>
                            <div class="order-detail-totals__grand">
                                <dt>Total</dt>
                                <dd>₹{{ number_format($order->total, 0) }}</dd>
                            </div>
                        </dl>

                        <div class="order-detail-summary__status">
                            <div class="order-detail-summary__status-row">
                                <span class="order-detail-summary__label">Order status</span>
                                <span class="order-status order-status--{{ $order->status }}">{{ $order->status_label }}</span>
                            </div>
                            <div class="order-detail-summary__status-row">
                                <span class="order-detail-summary__label">Payment</span>
                                <span class="status-badge {{ $order->payment_status_badge_classes }}">{{ $order->payment_status_label }}</span>
                            </div>
                        </div>

                        <p class="order-detail-summary__note">{{ $order->statusUpdateMessage() }}</p>

                        <div class="order-detail-summary__actions">
                            <a href="{{ route('shop.index') }}" class="btn-primary order-detail-summary__shop">
                                <span>Order again</span>
                            </a>
                            <a href="mailto:hillnestofficial@gmail.com?subject=Order%20{{ urlencode($order->order_number) }}" class="order-detail-summary__help">
                                Need help with this order?
                            </a>
                        </div>
                    </aside>
                </div>
            </main>
        </div>
    </div>
</section>
@endsection
