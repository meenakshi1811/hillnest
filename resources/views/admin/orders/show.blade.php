@extends('layouts.admin')

@section('title', 'Order ' . $order->order_number)

@section('content')
<a href="{{ route('admin.orders.index') }}" class="admin-back-link">← Orders</a>
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">{{ $order->order_number }}</h1>
        <p class="admin-page-header__subtitle">Placed {{ $order->created_at->format('d M Y, H:i') }}</p>
    </div>
</div>

<div class="admin-detail-grid">
    <div class="admin-form-grid">
        <div class="admin-card">
            <div class="admin-card__body">
                <h2 class="admin-card__title">Items</h2>
                <div class="admin-items-list admin-section-gap">
                    @foreach($order->items as $item)
                    <div class="admin-items-list__row">
                        <span>{{ $item->product_name }} @if($item->product_size)({{ $item->product_size }})@endif × {{ $item->quantity }}</span>
                        <strong>₹{{ number_format($item->line_total, 0) }}</strong>
                    </div>
                    @endforeach
                </div>
                <dl class="admin-dl">
                    <div class="admin-dl__row"><dt>Subtotal</dt><dd>₹{{ number_format($order->subtotal, 0) }}</dd></div>
                    @if($order->hasCoupon())
                    <div class="admin-dl__row"><dt>Coupon ({{ $order->coupon_code }})</dt><dd>-₹{{ number_format($order->discount_amount, 0) }}</dd></div>
                    @endif
                    <div class="admin-dl__row"><dt>Shipping</dt><dd>₹{{ number_format($order->shipping_fee, 0) }}</dd></div>
                    <div class="admin-dl__row admin-dl__row--total"><dt>Total</dt><dd>₹{{ number_format($order->total, 0) }}</dd></div>
                </dl>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card__body">
                <h2 class="admin-card__title">Customer & Shipping</h2>
                <div class="admin-section-gap" style="font-size:14px;color:var(--text-mid)">
                    <p><strong style="color:var(--forest)">{{ $order->customer_name }}</strong></p>
                    <p>{{ $order->customer_email }} · {{ $order->customer_phone }}</p>
                    <p style="margin-top:10px">{{ $order->shipping_address }}</p>
                    <p>{{ $order->city }}, {{ $order->state }} — {{ $order->pincode }}</p>
                    @if($order->notes)<p style="margin-top:10px;color:var(--text-light)">Notes: {{ $order->notes }}</p>@endif
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card admin-side-panel">
        <div class="admin-card__body">
            <h2 class="admin-card__title">Update Status</h2>
            <p style="margin-top:12px;font-size:14px;color:var(--text-mid)">
                Current:
                <span class="status-badge {{ $order->status_badge_classes }}">{{ $order->status_label }}</span>
            </p>
            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="admin-form-grid admin-section-gap">
                @csrf @method('PATCH')
                <div class="admin-field">
                    <label class="admin-label" for="order-status">Status</label>
                    <select id="order-status" name="status" class="admin-select">
                        @foreach(\App\Models\Order::STATUSES as $key => $label)
                            <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="admin-btn admin-btn--block">Update Status</button>
            </form>
            <div class="admin-meta">
                <p>Payment: {{ strtoupper($order->payment_method) }}</p>
                <p>
                    Payment status:
                    <span class="status-badge {{ $order->payment_status_badge_classes }}">{{ $order->payment_status_label }}</span>
                </p>
                @if($order->razorpay_payment_id)
                <p>Razorpay ID: <code class="admin-code">{{ $order->razorpay_payment_id }}</code></p>
                @endif
                @if($order->paid_at)
                <p>Paid at: {{ $order->paid_at->format('d M Y H:i') }}</p>
                @endif
                @if($order->payment_error)
                <p style="color:#8a3838">Error: {{ $order->payment_error }}</p>
                @endif
                <p>Placed: {{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
            @if($order->user)
            <a href="{{ route('admin.users.show', $order->user) }}" class="admin-inline-link">View customer profile →</a>
            @endif
        </div>
    </div>
</div>
@endsection
