@extends('layouts.app')

@section('title', 'Shopping Cart — Hillnest')

@section('content')
@php
    $itemCount = $items->sum('quantity');
    $freeShippingAt = 2000;
    $amountToFree = max(0, $freeShippingAt - $subtotal);
    $freeShippingProgress = $subtotal > 0 ? min(100, ($subtotal / $freeShippingAt) * 100) : 0;
@endphp

<section class="cart-page">
    <div class="cart-page-hero">
        <div class="cart-page-shell">
            <nav class="cart-page-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('shop.index') }}">Shop</a>
                <span aria-hidden="true">/</span>
                <span>Cart</span>
            </nav>
            <div class="cart-page-header">
                <p class="cart-page-eyebrow">Your Basket</p>
                <h1>Shopping Cart</h1>
                <p>Review your HillNest picks before they begin their journey from the mountains.</p>
                @if($items->count())
                    <span class="cart-page-count" data-cart-page-count>{{ $itemCount }} {{ Str::plural('item', $itemCount) }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="cart-page-shell cart-page-body">
        @if($items->count())
        <div class="cart-page-layout">
            <div class="cart-page-main">
                <div class="cart-items-toolbar" aria-hidden="true">
                    <span>Product</span>
                    <span>Quantity</span>
                    <span>Total</span>
                </div>

                <div class="cart-page-items" aria-label="Shopping cart items">
                    @foreach($items as $item)
                    <article
                        class="cart-line-item"
                        data-cart-line
                        data-product-id="{{ $item['product']->id }}"
                        data-unit-price="{{ $item['product']->price }}"
                    >
                        <a href="{{ route('shop.show', $item['product']) }}" class="cart-line-item__media">
                            <img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}">
                        </a>

                        <div class="cart-line-item__body">
                            <div class="cart-line-item__info">
                                <p class="cart-line-item__type">Pure Bilona Ghee</p>
                                <h2><a href="{{ route('shop.show', $item['product']) }}">{{ $item['product']->name }}</a></h2>
                                <p class="cart-line-item__size">{{ $item['product']->size }}</p>
                                <p class="cart-line-item__unit-price">₹{{ number_format($item['product']->price, 0) }} <span>each</span></p>
                            </div>

                            <div
                                class="cart-line-item__qty-row"
                                data-cart-update-form
                                data-update-url="{{ route('cart.update', $item['product']) }}"
                            >
                                <span class="cart-line-item__qty-label">Quantity</span>
                                <div class="cart-qty-stepper">
                                    <button type="button" class="cart-qty-stepper__btn" data-qty-minus aria-label="Decrease quantity">−</button>
                                    <input
                                        id="quantity-{{ $item['product']->id }}"
                                        type="number"
                                        name="quantity"
                                        value="{{ $item['quantity'] }}"
                                        min="1"
                                        max="20"
                                        data-qty-input
                                        readonly
                                        aria-live="polite"
                                    >
                                    <button type="button" class="cart-qty-stepper__btn" data-qty-plus aria-label="Increase quantity">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="cart-line-item__aside">
                            <div class="cart-line-item__totals">
                                <span class="cart-line-item__total-label">Line total</span>
                                <strong class="cart-line-item__total-val" data-line-total>₹{{ number_format($item['line_total'], 0) }}</strong>
                            </div>
                            <button
                                type="button"
                                class="cart-line-item__remove"
                                data-cart-page-remove
                                data-remove-url="{{ route('cart.remove', $item['product']) }}"
                                aria-label="Remove {{ $item['product']->name }} from cart"
                            >
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                                Remove
                            </button>
                        </div>
                    </article>
                    @endforeach
                </div>

                <a href="{{ route('shop.index') }}" class="cart-page-back">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                    Continue Shopping
                </a>
            </div>

            <aside class="cart-summary" aria-label="Order summary">
                <div class="cart-summary__head">
                    <p class="cart-page-eyebrow">Summary</p>
                    <h2>Order Summary</h2>
                </div>

                <ul class="cart-summary__items">
                    @foreach($items as $item)
                    <li data-summary-item data-product-id="{{ $item['product']->id }}">
                        <div class="cart-summary__item-thumb">
                            <img src="{{ $item['product']->image_url }}" alt="">
                        </div>
                        <div>
                            <span class="cart-summary__item-name">{{ $item['product']->card_title ?? $item['product']->name }}</span>
                            <span class="cart-summary__item-meta" data-summary-qty>Qty {{ $item['quantity'] }} · ₹{{ number_format($item['product']->price, 0) }}</span>
                        </div>
                        <strong data-summary-line-total>₹{{ number_format($item['line_total'], 0) }}</strong>
                    </li>
                    @endforeach
                </ul>

                <div class="cart-summary__shipping" data-shipping-block>
                @if($amountToFree > 0)
                <div class="cart-summary__shipping-progress">
                    <div class="cart-summary__shipping-progress-bar" data-shipping-progress style="width: {{ $freeShippingProgress }}%"></div>
                </div>
                <p class="cart-summary__shipping-msg" data-shipping-msg>
                    Add <strong>₹{{ number_format($amountToFree, 0) }}</strong> more for free shipping
                </p>
                @else
                <p class="cart-summary__shipping-msg cart-summary__shipping-msg--success" data-shipping-msg>
                    ✓ You qualify for free shipping
                </p>
                @endif
                </div>

                <dl class="cart-summary__totals">
                    <div>
                        <dt>Subtotal</dt>
                        <dd data-cart-subtotal>₹{{ number_format($subtotal, 0) }}</dd>
                    </div>
                    <div>
                        <dt>Shipping</dt>
                        <dd data-cart-shipping>{{ $shipping > 0 ? '₹'.number_format($shipping, 0) : 'FREE' }}</dd>
                    </div>
                    <div class="cart-summary__grand-total">
                        <dt>Total</dt>
                        <dd data-cart-total>₹{{ number_format($total, 0) }}</dd>
                    </div>
                </dl>

                <ul class="cart-summary__trust">
                    <li>
                        <span aria-hidden="true">🌿</span>
                        Pure A2 Bilona
                    </li>
                    <li>
                        <span aria-hidden="true">🏔</span>
                        From Upper Shimla
                    </li>
                </ul>

                @auth
                <a href="{{ route('checkout.index') }}" class="btn-primary cart-summary__checkout"><span>Proceed to Checkout</span></a>
                @else
                <a href="{{ route('login') }}" class="btn-primary cart-summary__checkout"><span>Log in to Checkout</span></a>
                <p class="cart-summary__login-note">Please <a href="{{ route('login') }}">log in</a> or <a href="{{ route('register') }}">register</a> to place an order.</p>
                @endauth
            </aside>
        </div>
        @else
        <div class="cart-page-empty">
            <div class="cart-page-empty__icon" aria-hidden="true">
                <svg width="38" height="38" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <p class="cart-page-eyebrow">Your cart is empty</p>
            <h2>Let’s fill it with golden goodness.</h2>
            <p>Browse pure A2 bilona ghee and add your favorite jar to begin checkout.</p>
            <a href="{{ route('shop.index') }}" class="btn-primary"><span>Browse Ghee</span></a>
        </div>
        @endif
    </div>
</section>
@endsection
