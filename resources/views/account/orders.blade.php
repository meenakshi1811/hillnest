@extends('layouts.app')

@section('title', 'My Orders — Hillnest')

@section('content')
<section class="account-orders-page">
    <div class="account-orders-shell">
        <div class="account-orders-hero">
            <div>
                <p class="account-eyebrow">Account</p>
                <h1>My Orders</h1>
                <p class="account-orders-intro">Track your HillNest purchases and revisit every delivery detail in one calm, organized place.</p>
            </div>
            <a href="{{ route('account.profile') }}" class="account-profile-link">Edit Profile</a>
        </div>

        @if($orders->count())
        <div class="orders-list" aria-label="Order history">
            @foreach($orders as $order)
            <a href="{{ route('account.orders.show', $order->order_number) }}" class="order-card">
                <div class="order-card__main">
                    <span class="order-card__label">Order number</span>
                    <p class="order-card__number">{{ $order->order_number }}</p>
                    <p class="order-card__date">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                </div>
                <div class="order-card__meta">
                    <p class="order-card__total">₹{{ number_format($order->total, 0) }}</p>
                    <span class="order-status order-status--{{ $order->status }}">{{ $order->status_label }}</span>
                </div>
                <span class="order-card__arrow" aria-hidden="true">→</span>
            </a>
            @endforeach
        </div>
        <div class="orders-pagination">{{ $orders->links() }}</div>
        @else
        <div class="orders-empty">
            <div class="orders-empty__icon" aria-hidden="true">
                <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <p class="orders-empty__eyebrow">Your shelf is waiting</p>
            <h2>No orders yet</h2>
            <p>Start with our pure A2 bilona ghee and your order history will appear here.</p>
            <a href="{{ route('shop.index') }}" class="btn-primary"><span>Start Shopping</span></a>
        </div>
        @endif
    </div>
</section>
@endsection
