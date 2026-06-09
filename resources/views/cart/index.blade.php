@extends('layouts.app')

@section('title', 'Shopping Cart — Hillnest')

@section('content')
<section class="cart-page">
    <div class="cart-page-shell">
        <div class="cart-page-header">
            <p class="cart-page-eyebrow">Your Basket</p>
            <h1>Shopping Cart</h1>
            <p>Review your HillNest picks before they begin their journey from the mountains.</p>
        </div>

        @if($items->count())
        <div class="cart-page-layout">
            <div class="cart-page-items" aria-label="Shopping cart items">
                @foreach($items as $item)
                <article class="cart-line-item">
                    <a href="{{ route('shop.show', $item['product']) }}" class="cart-line-item__media">
                        <img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}">
                    </a>

                    <div class="cart-line-item__details">
                        <p class="cart-line-item__type">Bilona Ghee</p>
                        <h2><a href="{{ route('shop.show', $item['product']) }}">{{ $item['product']->name }}</a></h2>
                        <p class="cart-line-item__size">{{ $item['product']->size }}</p>
                        <p class="cart-line-item__price">₹{{ number_format($item['product']->price, 0) }}</p>

                        <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="cart-line-item__quantity-form">
                            @csrf
                            @method('PATCH')
                            <label for="quantity-{{ $item['product']->id ?? $loop->index }}">Qty</label>
                            <input id="quantity-{{ $item['product']->id ?? $loop->index }}" type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" max="20">
                            <button type="submit">Update</button>
                        </form>
                    </div>

                    <div class="cart-line-item__totals">
                        <span>Line total</span>
                        <strong>₹{{ number_format($item['line_total'], 0) }}</strong>
                        <form action="{{ route('cart.remove', $item['product']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Remove</button>
                        </form>
                    </div>
                </article>
                @endforeach
            </div>

            <aside class="cart-summary" aria-label="Order summary">
                <p class="cart-page-eyebrow">Summary</p>
                <h2>Order Summary</h2>
                <dl class="cart-summary__totals">
                    <div>
                        <dt>Subtotal</dt>
                        <dd>₹{{ number_format($subtotal, 0) }}</dd>
                    </div>
                    <div>
                        <dt>Shipping</dt>
                        <dd>{{ $shipping > 0 ? '₹'.number_format($shipping, 0) : 'FREE' }}</dd>
                    </div>
                    @if($shipping > 0)
                    <p class="cart-summary__note">Free shipping on orders above ₹2,000</p>
                    @endif
                    <div class="cart-summary__grand-total">
                        <dt>Total</dt>
                        <dd>₹{{ number_format($total, 0) }}</dd>
                    </div>
                </dl>
                <a href="{{ route('checkout.index') }}" class="btn-primary cart-summary__checkout"><span>Check Out</span></a>
                <a href="{{ route('shop.index') }}" class="cart-summary__continue">Continue Shopping</a>
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
