@extends('layouts.app')

@section('title', 'My Orders — Hillnest')

@section('content')
<section class="account-page">
    <div class="account-page-hero">
        <div class="account-page-shell">
            <nav class="account-page-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>My Account</span>
            </nav>
            <div class="account-page-header">
                <p class="account-page-eyebrow">Your HillNest Account</p>
                <h1>My Orders</h1>
                <p>Every jar of golden ghee you have ordered — tracked beautifully in one place.</p>
            </div>
        </div>
    </div>

    <div class="account-page-shell account-page-body">
        <div class="account-layout">
            @include('account.partials.sidebar', ['active' => 'orders'])

            <main class="account-main">
                <div class="account-main__head">
                    <div>
                        <p class="account-page-eyebrow">Order history</p>
                        <h2>{{ $orders->total() > 0 ? $orders->total() . ' ' . Str::plural('order', $orders->total()) : 'No orders yet' }}</h2>
                    </div>
                    @if($orders->total() > 0)
                    <a href="{{ route('shop.index') }}" class="account-main__shop-link">Order again</a>
                    @endif
                </div>

                @if($orders->count())
                <div class="order-panels">
                    @foreach($orders as $order)
                    @php $itemCount = $order->items->sum('quantity'); @endphp
                    <article class="order-panel">
                        <div class="order-panel__head">
                            <div class="order-panel__identity">
                                <span class="order-panel__label">Order</span>
                                <strong>{{ $order->order_number }}</strong>
                            </div>
                            <span class="order-status order-status--{{ $order->status }}">{{ $order->status_label }}</span>
                            <time class="order-panel__date" datetime="{{ $order->created_at->toIso8601String() }}">
                                {{ $order->created_at->format('d M Y') }}
                            </time>
                        </div>

                        <div class="order-panel__items">
                            @foreach($order->items as $item)
                            <div class="order-panel__item">
                                <div class="order-panel__item-thumb">
                                    @if($item->product?->image_url)
                                        <img src="{{ $item->product->image_url }}" alt="">
                                    @else
                                        <span aria-hidden="true">🫙</span>
                                    @endif
                                </div>
                                <div class="order-panel__item-info">
                                    <strong>{{ $item->product_name }}</strong>
                                    @if($item->product_size)
                                        <span>{{ $item->product_size }}</span>
                                    @endif
                                    <span>Qty {{ $item->quantity }} · ₹{{ number_format($item->line_total, 0) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="order-panel__foot">
                            <div class="order-panel__summary">
                                <span>
                                    {{ $itemCount }} {{ Str::plural('item', $itemCount) }}
                                    @if($order->hasCoupon())
                                        · Coupon {{ $order->coupon_code }} (-₹{{ number_format($order->discount_amount, 0) }})
                                    @endif
                                </span>
                                <strong>₹{{ number_format($order->total, 0) }}</strong>
                            </div>
                            <div class="order-panel__actions">
                                @if($order->canShowTracking() && $order->tracking_url)
                                    <a href="{{ $order->tracking_url }}" class="order-panel__track" target="_blank" rel="noopener noreferrer">Track order</a>
                                @elseif($order->canShowTracking())
                                    <a href="{{ route('account.orders.show', $order->order_number) }}#tracking" class="order-panel__track">Track order</a>
                                @endif
                                <a href="{{ route('account.orders.show', $order->order_number) }}" class="order-panel__cta">
                                    View order
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>

                @if($orders->hasPages())
                <div class="orders-pagination">{{ $orders->links('vendor.pagination.hillnest') }}</div>
                @endif
                @else
                <div class="orders-empty">
                    <div class="orders-empty__visual" aria-hidden="true">
                        <span>🫙</span>
                    </div>
                    <h3>Your order shelf is empty</h3>
                    <p>Pure A2 bilona ghee from upper Shimla is waiting. Place your first order and it will show up here.</p>
                    <a href="{{ route('shop.index') }}" class="btn-primary"><span>Explore Ghee</span></a>
                </div>
                @endif
            </main>
        </div>
    </div>
</section>
@endsection
