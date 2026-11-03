@extends('layouts.app')

@section('title', $coupon ? 'Diwali Offer: Extra '.$coupon->value_label.' off — HillNest' : 'Diwali Offer — HillNest')
@section('meta_description', 'Unwrap your HillNest Diwali gift: an extra discount on pure A2 bilona ghee from Upper Shimla. Open the gift to reveal your coupon code.')
@section('fireworks', 'page')

@section('content')
<section class="diwali-hero" data-diwali>
    <canvas class="diwali-hero__fireworks" data-diwali-fireworks aria-hidden="true"></canvas>
    <div class="diwali-hero__stars" aria-hidden="true"></div>
    <div class="diwali-hero__garland" aria-hidden="true"></div>

    <div class="diwali-hero__inner">
        <p class="diwali-hero__eyebrow"><span aria-hidden="true">🪔</span> Diwali Sale is Live</p>

        @if($coupon)
            <h1 class="diwali-hero__title">A Diwali gift <em>from the hills</em></h1>
            <p class="diwali-hero__lead">Light up your festive kitchen with pure A2 bilona ghee, hand-churned in the village homes of Upper Shimla. We've wrapped a little something for you.</p>

            <div class="diwali-stage" data-diwali-stage>
                <div class="diwali-stage__rays" aria-hidden="true"></div>

                <button type="button" class="diwali-gift" data-diwali-gift aria-label="Open your Diwali gift to reveal the coupon code">
                    <span class="diwali-gift__lid"><span class="diwali-gift__bow"></span></span>
                    <span class="diwali-gift__base"></span>
                </button>
                <p class="diwali-stage__hint" data-diwali-hint>✨ Tap the gift to open it</p>

                <div class="diwali-coupon" data-diwali-coupon hidden aria-live="polite">
                    <p class="diwali-coupon__label">Your Diwali coupon</p>
                    <p class="diwali-coupon__value">Extra <strong>{{ $coupon->value_label }} off</strong> your order</p>
                    <div class="diwali-coupon__code-box">
                        <span class="diwali-coupon__code">{{ $coupon->code }}</span>
                        <button type="button" class="diwali-coupon__copy" data-diwali-copy="{{ $coupon->code }}">Copy</button>
                    </div>
                    <p class="diwali-coupon__meta">
                        @if($coupon->expires_at)
                            {{-- Coupons stop working at the start of their expiry date, so the last full day is the day before. --}}
                            Valid till {{ $coupon->expires_at->copy()->subSecond()->format('d M Y') }} ·
                        @endif
                        One use per customer
                    </p>
                    <form method="POST" action="{{ route('diwali.apply') }}">
                        @csrf
                        <button type="submit" class="diwali-coupon__apply">Apply &amp; shop now</button>
                    </form>
                    <p class="diwali-coupon__note">Or enter the code in your cart or at checkout.</p>
                </div>
            </div>
        @else
            <h1 class="diwali-hero__title">Our Diwali offer <em>has ended</em></h1>
            <p class="diwali-hero__lead">Thank you for celebrating with us. Our pure A2 bilona ghee is still here for your festive kitchen.</p>
            <a href="{{ route('shop.index') }}" class="diwali-coupon__apply diwali-hero__shop">Shop ghee</a>
        @endif
    </div>

    <div class="diwali-diyas" aria-hidden="true">
        @for($i = 0; $i < 7; $i++)
            <span class="diwali-diya"><span class="diwali-diya__flame"></span></span>
        @endfor
    </div>
</section>

@if($coupon)
<section class="diwali-steps">
    <div class="shop-shell">
        <ol class="diwali-steps__list">
            <li>
                <span class="diwali-steps__num">1</span>
                <strong>Open your gift</strong>
                <p>Reveal the code above and tap <em>Apply &amp; shop now</em>.</p>
            </li>
            <li>
                <span class="diwali-steps__num">2</span>
                <strong>Fill your cart</strong>
                <p>Pick your jars. The {{ $coupon->value_label }} discount shows in your cart.</p>
            </li>
            <li>
                <span class="diwali-steps__num">3</span>
                <strong>Celebrate</strong>
                <p>Check out and we'll ship your ghee from Upper Shimla.</p>
            </li>
        </ol>
    </div>
</section>
@endif

@if($products->count())
<section class="diwali-products">
    <div class="shop-shell">
        <div class="product-detail-related__header">
            <p class="shop-eyebrow">Festive picks</p>
            <h2 class="section-title">Pick your <em>Diwali ghee</em></h2>
            @if($coupon)
                <p class="diwali-products__note">Prices drop by a further {{ $coupon->value_label }} with <strong>{{ $coupon->code }}</strong>.</p>
            @endif
        </div>
        <div class="collection-showcase">
            <div class="shop-products-grid">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
@if($coupon)
<script src="{{ asset('js/festive-fireworks.js') }}?v={{ filemtime(public_path('js/festive-fireworks.js')) }}"></script>
<script src="{{ asset('js/diwali-offer.js') }}?v={{ filemtime(public_path('js/diwali-offer.js')) }}"></script>
@endif
@endpush
