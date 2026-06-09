@props(['product'])

<article class="product-card-rosier">
    <a href="{{ route('shop.show', $product) }}" class="product-card-rosier__media">
        <img
            src="{{ $product->image_url }}"
            alt="{{ $product->name }}"
            loading="lazy"
        >
        @if($product->badge)
            <span class="product-card-rosier__badge product-card-rosier__badge--gold">{{ $product->badge }}</span>
        @elseif($product->is_featured)
            <span class="product-card-rosier__badge product-card-rosier__badge--forest">Best Seller</span>
        @endif
        @if($product->compare_price && $product->compare_price > $product->price)
            <span class="product-card-rosier__sale">Sale</span>
        @endif
    </a>

    <div class="product-card-rosier__body">
        <p class="product-card-rosier__type">Ghee</p>

        <h3>
            <a href="{{ route('shop.show', $product) }}">{{ $product->name }}</a>
        </h3>

        @if($product->reviews_count > 0)
        <div class="product-card-rosier__reviews">
            <span>★★★★★</span>
            <span>{{ number_format($product->reviews_count) }} reviews</span>
        </div>
        @endif

        <p class="product-card-rosier__size">{{ $product->size }}</p>

        <div class="product-card-rosier__price-row">
            <span class="product-card-rosier__price">₹{{ number_format($product->price, 0) }}</span>
            @if($product->compare_price && $product->compare_price > $product->price)
                <span class="product-card-rosier__compare">₹{{ number_format($product->compare_price, 0) }}</span>
            @endif
        </div>

        <form action="{{ route('cart.add', $product) }}" method="POST" class="product-card-rosier__form">
            @csrf
            <button type="submit" class="btn-primary"><span>Add to Cart</span></button>
        </form>
    </div>
</article>
