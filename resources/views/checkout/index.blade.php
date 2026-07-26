@extends('layouts.app')

@section('title', 'Checkout — Hillnest')

@section('content')
@php
    $itemCount = $items->sum('quantity');
    $freeShippingAt = 2000;
    $amountToFree = max(0, $freeShippingAt - $subtotal);
    $discount = $discount ?? 0;
    $appliedCoupon = $appliedCoupon ?? null;
@endphp

<section class="checkout-page">
    <div class="checkout-page-hero">
        <div class="checkout-shell">
            <nav class="checkout-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('cart.index') }}">Cart</a>
                <span aria-hidden="true">/</span>
                <span>Checkout</span>
            </nav>
            <div class="checkout-page-header">
                <p class="checkout-eyebrow">Secure Checkout</p>
                <h1>Complete Your Order</h1>
                <p>Just a few details and your HillNest ghee will be on its way from the mountains.</p>
                <span class="checkout-page-count">{{ $itemCount }} {{ Str::plural('item', $itemCount) }}</span>
            </div>
        </div>
    </div>

    <div class="checkout-shell checkout-page-body">
        <form method="POST" class="checkout-layout" data-checkout-form novalidate>
            @csrf

            <div class="checkout-main">
                <article class="checkout-card">
                    <div class="checkout-card__head">
                        <p class="checkout-eyebrow">Delivery</p>
                        <h2>Contact &amp; Shipping</h2>
                    </div>

                    <div class="checkout-fields">
                        <div class="checkout-field checkout-field--full">
                            <label class="checkout-label" for="customer_name">Full Name *</label>
                            <input class="checkout-input" type="text" id="customer_name" name="customer_name" value="{{ old('customer_name', $user?->name) }}" required autocomplete="name">
                            @error('customer_name')<p class="checkout-field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="checkout-field">
                            <label class="checkout-label" for="customer_email">Email *</label>
                            <input class="checkout-input" type="email" id="customer_email" name="customer_email" value="{{ old('customer_email', $user?->email) }}" required autocomplete="email">
                            @error('customer_email')<p class="checkout-field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="checkout-field">
                            <label class="checkout-label" for="customer_phone">Phone *</label>
                            <input class="checkout-input" type="text" id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $user?->phone) }}" required autocomplete="tel">
                            @error('customer_phone')<p class="checkout-field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="checkout-field checkout-field--full">
                            <label class="checkout-label" for="shipping_address">Address *</label>
                            <textarea class="checkout-textarea" id="shipping_address" name="shipping_address" rows="3" required autocomplete="street-address">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')<p class="checkout-field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="checkout-field">
                            <label class="checkout-label" for="city">City *</label>
                            <input class="checkout-input" type="text" id="city" name="city" value="{{ old('city') }}" required autocomplete="address-level2">
                            @error('city')<p class="checkout-field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="checkout-field">
                            <label class="checkout-label" for="state">State *</label>
                            <input class="checkout-input" type="text" id="state" name="state" value="{{ old('state', 'Himachal Pradesh') }}" required autocomplete="address-level1">
                            @error('state')<p class="checkout-field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="checkout-field">
                            <label class="checkout-label" for="pincode">Pincode *</label>
                            <input class="checkout-input" type="text" id="pincode" name="pincode" value="{{ old('pincode') }}" required autocomplete="postal-code">
                            @error('pincode')<p class="checkout-field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="checkout-field checkout-field--full">
                            <label class="checkout-label" for="notes">Order Notes</label>
                            <textarea class="checkout-textarea" id="notes" name="notes" rows="2" placeholder="Delivery instructions (optional)">{{ old('notes') }}</textarea>
                            @error('notes')<p class="checkout-field-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </article>

                <a href="{{ route('cart.index') }}" class="checkout-back">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                    Back to Cart
                </a>
            </div>

            <aside class="checkout-summary" aria-label="Order summary">
                <div class="checkout-summary__head">
                    <p class="checkout-eyebrow">Summary</p>
                    <h2>Your Order</h2>
                </div>

                <ul class="checkout-summary__items">
                    @foreach($items as $item)
                    <li>
                        <div class="checkout-summary__item-thumb">
                            <img src="{{ $item['product']->image_url }}" alt="">
                        </div>
                        <div>
                            <span class="checkout-summary__item-name">{{ $item['product']->name }}</span>
                            <span class="checkout-summary__item-meta">Qty {{ $item['quantity'] }} · ₹{{ number_format($item['product']->price, 0) }}</span>
                        </div>
                        <strong>₹{{ number_format($item['line_total'], 0) }}</strong>
                    </li>
                    @endforeach
                </ul>

                @if($amountToFree > 0)
                <p class="checkout-summary__note">
                    Add <strong>₹{{ number_format($amountToFree, 0) }}</strong> more on future orders for free shipping
                </p>
                @endif

                <div class="checkout-coupon" data-checkout-coupon>
                    <label class="checkout-label" for="coupon_code">Coupon code</label>
                    <div class="checkout-coupon__row">
                        <input
                            class="checkout-input"
                            type="text"
                            id="coupon_code"
                            name="coupon_code"
                            value="{{ old('coupon_code', $appliedCoupon?->code) }}"
                            placeholder="Enter your coupon"
                            autocomplete="off"
                            @if($appliedCoupon) readonly @endif
                            data-coupon-input
                        >
                        @if($appliedCoupon)
                            <button type="button" class="checkout-coupon__btn checkout-coupon__btn--remove" data-coupon-remove>Remove</button>
                        @else
                            <button type="button" class="checkout-coupon__btn" data-coupon-apply>Apply</button>
                        @endif
                    </div>
                    <p class="checkout-field-error" data-coupon-error @unless($errors->has('coupon_code')) hidden @endunless>{{ $errors->first('coupon_code') }}</p>
                    @if($appliedCoupon)
                    <p class="checkout-coupon__applied">
                        <span>{{ $appliedCoupon->code }}</span> applied — {{ $appliedCoupon->value_label }} off
                    </p>
                    @endif
                </div>

                <dl class="checkout-summary__totals" data-checkout-totals>
                    <div>
                        <dt>Subtotal</dt>
                        <dd data-total-subtotal>₹{{ number_format($subtotal, 0) }}</dd>
                    </div>
                    <div data-total-discount-row @unless($discount > 0) hidden @endunless>
                        <dt>Coupon discount</dt>
                        <dd data-total-discount>-₹{{ number_format($discount, 0) }}</dd>
                    </div>
                    <div>
                        <dt>Shipping</dt>
                        <dd data-total-shipping>{{ $shipping > 0 ? '₹'.number_format($shipping, 0) : 'FREE' }}</dd>
                    </div>
                    <div class="checkout-summary__grand-total">
                        <dt>Total</dt>
                        <dd data-total-grand>₹{{ number_format($total, 0) }}</dd>
                    </div>
                </dl>

                <ul class="checkout-summary__trust">
                    <li><span aria-hidden="true">🌿</span> Pure A2 Bilona</li>
                    <li><span aria-hidden="true">🏔</span> From Upper Shimla</li>
                    <li><span aria-hidden="true">🔒</span> Secure payment via Razorpay</li>
                </ul>

                <p class="checkout-field-error" data-payment-error hidden></p>

                <button type="submit" class="btn-primary checkout-summary__submit" data-checkout-submit>
                    <span>Pay ₹{{ number_format($total, 0) }}</span>
                </button>
            </aside>
        </form>
    </div>
</section>

<div class="payment-overlay" data-payment-overlay hidden aria-live="polite" aria-busy="true">
    <div class="payment-overlay__panel" role="alertdialog" aria-modal="true" aria-labelledby="payment-overlay-title">
        <div class="payment-overlay__spinner" aria-hidden="true"></div>
        <h2 id="payment-overlay-title" class="payment-overlay__title">Processing Payment</h2>
        <p class="payment-overlay__message" data-payment-overlay-message>
            Please do not close or reload this page while your payment is being processed.
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.checkoutCouponApplyUrl = @json(route('checkout.coupon.apply'));
    window.checkoutCouponRemoveUrl = @json(route('checkout.coupon.remove'));
    window.checkoutPaymentCreateUrl = @json(route('checkout.payment.create'));
    window.checkoutPaymentVerifyUrl = @json(route('checkout.payment.verify'));
    window.checkoutPaymentFailedUrl = @json(route('checkout.payment.failed'));
    window.checkoutBrandName = @json(config('app.name', 'Hillnest'));
</script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="{{ asset('js/checkout-coupon.js') }}"></script>
<script src="{{ asset('js/checkout-payment.js') }}?v={{ filemtime(public_path('js/checkout-payment.js')) }}"></script>
@endpush
