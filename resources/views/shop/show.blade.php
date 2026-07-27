@extends('layouts.app')

@section('title', $product->name . ' — Hillnest')

@section('content')
<section class="product-detail">
    <div class="product-detail-hero">
        <div class="shop-shell">
            <nav class="product-detail-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('shop.index') }}">Shop</a>
                <span aria-hidden="true">/</span>
                <span>{{ $product->card_title }}</span>
            </nav>
        </div>
    </div>

    <div class="product-detail-body shop-shell">
        <div class="product-detail-grid">
            <div class="product-detail-gallery">
                <div class="product-detail-image-wrap">
                    @if($product->display_badges)
                        <x-product-badges :product="$product" class="product-detail-badges" />
                    @endif
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                </div>
            </div>

            <div class="product-detail-info">
                <p class="product-detail-eyebrow">{{ $product->size }} · Pure Bilona Ghee</p>
                <h1 class="product-detail-title">{{ $product->name }}</h1>

                @if($product->reviews_count > 0)
                <div class="product-detail-reviews">
                    <x-star-rating :rating="$product->displayRating()" />
                    <span>{{ number_format($product->reviews_count) }} {{ Str::plural('review', $product->reviews_count) }}</span>
                </div>
                @endif

                @if($product->short_description)
                <p class="product-detail-lead">{{ $product->short_description }}</p>
                @endif

                <div class="product-detail-price-block">
                    <div class="product-detail-price-group">
                        <span class="product-detail-price">₹{{ number_format($product->price, 0) }}</span>
                        @if($product->compare_price && $product->compare_price > $product->price)
                            <span class="product-detail-compare">₹{{ number_format($product->compare_price, 0) }}</span>
                        @endif
                    </div>
                    @if($product->is_on_sale)
                        <span class="product-detail-save">Save {{ $product->discount_percent }}%</span>
                    @endif
                </div>

                <p class="product-detail-stock {{ $product->stock > 0 ? 'product-detail-stock--in' : 'product-detail-stock--out' }}">
                    {{ $product->stock > 0 ? '✓ In stock — ships from upper Shimla' : 'Out of stock' }}
                </p>

                @if($product->stock > 0)
                <x-cart-action :product="$product" :show-quantity="true" variant="page" />
                @endif

                <ul class="product-detail-trust">
                    <li><span aria-hidden="true">🌿</span> Pure A2 Bilona</li>
                    <li><span aria-hidden="true">🏔</span> From Upper Shimla</li>
                    <li><span aria-hidden="true">🫙</span> Small-batch crafted</li>
                </ul>
            </div>
        </div>

        <div class="product-detail-panels">
            <article class="product-detail-panel">
                <p class="product-detail-panel__eyebrow">About this ghee</p>
                <h2>Description</h2>
                <div class="product-detail-panel__content">
                    <p class="product-detail-description">{{ $product->description }}</p>
                </div>
            </article>

            <aside class="product-detail-highlights">
                <p class="product-detail-panel__eyebrow">Why HillNest</p>
                <h2>Crafted with care</h2>
                <ul>
                    <li>
                        <span class="product-detail-highlights__icon" aria-hidden="true">01</span>
                        <div>
                            <strong>Traditional bilona</strong>
                            <p>Slow-churned the authentic way for rich aroma and golden clarity.</p>
                        </div>
                    </li>
                    <li>
                        <span class="product-detail-highlights__icon" aria-hidden="true">02</span>
                        <div>
                            <strong>Pure A2 desi cow</strong>
                            <p>Sourced from indigenous cows raised in the Himalayan foothills.</p>
                        </div>
                    </li>
                    <li>
                        <span class="product-detail-highlights__icon" aria-hidden="true">03</span>
                        <div>
                            <strong>Upper Shimla origin</strong>
                            <p>Every jar travels from our mountain home straight to your kitchen.</p>
                        </div>
                    </li>
                </ul>
            </aside>
        </div>

        @if($reviews->count())
        <div class="product-detail-reviews-section" id="reviews">
            <div class="product-detail-reviews-section__head">
                <p class="product-detail-panel__eyebrow">Customer voices</p>
                <h2>What families are saying</h2>
                <x-star-rating :rating="$product->displayRating()" :count="$product->reviews_count" show-value />
            </div>

            <div class="product-reviews-grid">
                @foreach($reviews as $review)
                <article class="product-review-card">
                    <div class="product-review-card__top">
                        <x-star-rating :rating="$review->rating" />
                        <time datetime="{{ $review->created_at->toIso8601String() }}">{{ $review->created_at->format('d M Y') }}</time>
                    </div>
                    @if($review->comment)
                        <blockquote class="product-review-card__text">"{{ $review->comment }}"</blockquote>
                    @endif
                    <footer class="product-review-card__author">
                        <span class="product-review-card__avatar" aria-hidden="true">{{ strtoupper(substr($review->user->name, 0, 1)) }}</span>
                        <cite>{{ $review->user->name }}</cite>
                        <span class="product-review-card__badge">Verified Buyer</span>
                    </footer>
                </article>
                @endforeach
            </div>
        </div>
        @endif

        @if($related->count())
        <div class="product-detail-related">
            <div class="product-detail-related__header">
                <p class="shop-eyebrow">Complete your pantry</p>
                <h2 class="section-title">You May Also <em>Like</em></h2>
            </div>
            <div class="collection-showcase">
                <div class="shop-products-grid">
                    @foreach($related as $item)
                        <x-product-card :product="$item" />
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
