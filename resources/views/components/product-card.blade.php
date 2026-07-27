@props(['product'])

<article class="product-card-rosier">
    <div class="product-card-rosier__visual">
        <a href="{{ route('shop.show', $product) }}" class="product-card-rosier__media" aria-label="View {{ $product->card_title }} details">
            <div class="product-card-rosier__media-bg" aria-hidden="true"></div>
            <div class="product-card-rosier__media-glow" aria-hidden="true"></div>
            <img
                src="{{ $product->image_url }}"
                alt="{{ $product->name }}"
                loading="lazy"
            >
        </a>

        @if($product->display_badges)
            <x-product-badges :product="$product" />
        @endif
    </div>

    <div class="product-card-rosier__body">
        <div class="product-card-rosier__head">
            <p class="product-card-rosier__type">Pure Bilona Ghee</p>
            <h3>
                <a href="{{ route('shop.show', $product) }}">{{ $product->card_title }}</a>
            </h3>
            @if($product->short_description)
                <p class="product-card-rosier__desc">{{ $product->short_description }}</p>
            @endif
        </div>

        <div class="product-card-rosier__meta">
            @if($product->reviews_count > 0)
            <div class="product-card-rosier__reviews" aria-label="{{ number_format($product->reviews_count) }} customer reviews">
                <x-star-rating :rating="$product->displayRating()" class="product-card-rosier__star-rating" />
                <span>{{ number_format($product->reviews_count) }} reviews</span>
            </div>
            @else
            <span class="product-card-rosier__reviews product-card-rosier__reviews--empty">Himalayan sourced</span>
            @endif

            <div class="product-card-rosier__price-block">
                <div class="product-card-rosier__price-group">
                    <span class="product-card-rosier__price">₹{{ number_format($product->price, 0) }}</span>
                    @if($product->is_on_sale)
                        <span class="product-card-rosier__compare">₹{{ number_format($product->compare_price, 0) }}</span>
                    @endif
                </div>
                @if($product->is_on_sale)
                    <span class="product-card-rosier__save">Save {{ $product->discount_percent }}%</span>
                @endif
            </div>
        </div>

        <x-cart-action :product="$product" />
    </div>
</article>
