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

                {{-- @if($product->reviews_count > 0)
                <div class="product-detail-reviews">
                    <x-star-rating :rating="$product->displayRating()" />
                    <span>{{ number_format($product->reviews_count) }} {{ Str::plural('review', $product->reviews_count) }}</span>
                </div>
                @endif --}}

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
            <div class="product-detail-main-col">
                <article class="product-detail-panel">
                    <p class="product-detail-panel__eyebrow">About this ghee</p>
                    <h2>Description</h2>
                    <div class="product-detail-panel__content">
                        <p class="product-detail-description">{{ $product->description }}</p>
                    </div>
                </article>

                <article class="product-detail-panel product-detail-care">
                    <p class="product-detail-panel__eyebrow">Storage &amp; handling</p>
                    <h2>Keep it pure</h2>
                    <p class="product-detail-care__intro">A little care goes a long way. Follow these simple habits to protect the aroma, texture, and shelf life of your HillNest ghee.</p>
                    <ul class="product-detail-care__list">
                        <li>
                            <span class="product-detail-care__icon" aria-hidden="true">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 7h14v11a3 3 0 0 1-3 3H8a3 3 0 0 1-3-3V7z"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M5 12h14"/><path d="M9 16h6"/></svg>
                            </span>
                            <div>
                                <strong>Do not refrigerate</strong>
                                <p>Store your ghee outside the fridge, in a cool, dry place away from direct sunlight. Refrigeration can harden the texture and is not needed for pure bilona ghee.</p>
                            </div>
                        </li>
                        <li>
                            <span class="product-detail-care__icon" aria-hidden="true">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 14c0 2.2 1.8 4 4 4s4-1.8 4-4"/><path d="M7 10V8a5 5 0 0 1 10 0v2"/><path d="M5 10h14v2a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-2z"/><path d="M4 4l16 16"/></svg>
                            </span>
                            <div>
                                <strong>Never touch with bare hands</strong>
                                <p>Always use a clean, dry spoon. Moisture and bare hands can introduce bacteria and shorten the shelf life of your ghee.</p>
                            </div>
                        </li>
                    </ul>
                </article>
            </div>

            <aside class="product-detail-highlights">
                <p class="product-detail-panel__eyebrow">Why HillNest</p>
                <h2>Crafted with care</h2>
                <p class="product-detail-highlights__intro">How we make HillNest ghee in our village — the same way families in Upper Shimla have done for generations.</p>
                <ul>
                    <li>
                        <span class="product-detail-highlights__icon" aria-hidden="true">01</span>
                        <div>
                            <strong>Fresh milk from village homes</strong>
                            <p>Each morning, A2 milk is collected from local families in Chhajpur whose free-grazing cows feed on high-altitude pastures.</p>
                        </div>
                    </li>
                    <li>
                        <span class="product-detail-highlights__icon" aria-hidden="true">02</span>
                        <div>
                            <strong>Curd set the traditional way</strong>
                            <p>Milk is gently warmed and cultured overnight in earthen pots — no machines, no additives, only time and natural cultures.</p>
                        </div>
                    </li>
                    <li>
                        <span class="product-detail-highlights__icon" aria-hidden="true">03</span>
                        <div>
                            <strong>Hand-churned bilona</strong>
                            <p>Village women hand-churn the curd in wooden bilona churners until rich butter separates — slow, patient work that cannot be rushed.</p>
                        </div>
                    </li>
                    <li>
                        <span class="product-detail-highlights__icon" aria-hidden="true">04</span>
                        <div>
                            <strong>Slow-cooked over wood fire</strong>
                            <p>Butter is clarified in small batches over a wood fire, releasing the golden colour and nutty aroma that define true Himalayan ghee.</p>
                        </div>
                    </li>
                    <li>
                        <span class="product-detail-highlights__icon" aria-hidden="true">05</span>
                        <div>
                            <strong>Packed in our village</strong>
                            <p>Every jar is strained, cooled, and sealed by hand in our village workshop before it leaves Upper Shimla for your kitchen.</p>
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
                    @if($review->hasImages())
                        <div class="review-photos">
                            @foreach($review->imageUrls() as $url)
                                <a href="{{ $url }}" class="review-photos__item" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ $url }}" alt="Review photo from {{ $review->user->publicDisplayName() }}">
                                </a>
                            @endforeach
                        </div>
                    @endif
                    <footer class="product-review-card__author">
                        <span class="product-review-card__avatar" aria-hidden="true">{{ strtoupper(substr($review->user->name, 0, 1)) }}</span>
                        <cite>{{ $review->user->publicDisplayName() }}</cite>
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
