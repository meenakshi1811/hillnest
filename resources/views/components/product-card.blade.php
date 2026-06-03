@props(['product'])

<article class="product-card-rosier group min-w-[280px] sm:min-w-0">
    <a href="{{ route('shop.show', $product) }}" class="relative block aspect-[4/5] overflow-hidden bg-cream-dark">
        <img
            src="{{ $product->image_url }}"
            alt="{{ $product->name }}"
            class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
            loading="lazy"
        >
        @if($product->badge)
            <span class="absolute top-4 left-4 bg-gold text-white text-xs font-bold uppercase tracking-wider px-3 py-1">{{ $product->badge }}</span>
        @elseif($product->is_featured)
            <span class="absolute top-4 left-4 bg-brand text-white text-xs font-bold uppercase tracking-wider px-3 py-1">🔥 Best Seller</span>
        @endif
        @if($product->compare_price && $product->compare_price > $product->price)
            <span class="absolute top-4 right-4 bg-red-600 text-white text-xs font-bold px-3 py-1">Sale</span>
        @endif
    </a>

    <div class="flex flex-1 flex-col p-5 md:p-6 text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gold mb-2">Ghee</p>

        <h3 class="font-display text-xl md:text-2xl font-semibold text-brand leading-snug min-h-[3.5rem] flex items-center justify-center">
            <a href="{{ route('shop.show', $product) }}" class="hover:text-gold transition line-clamp-2">{{ $product->name }}</a>
        </h3>

        @if($product->reviews_count > 0)
        <div class="mt-3 flex items-center justify-center gap-1.5 text-sm text-brand-light">
            <span class="text-gold tracking-tight">★★★★★</span>
            <span>{{ number_format($product->reviews_count) }} reviews</span>
        </div>
        @endif

        <p class="mt-2 text-base font-medium text-brand-light">{{ $product->size }}</p>

        <div class="mt-4 flex items-center justify-center gap-2">
            <span class="text-2xl font-bold text-brand">₹{{ number_format($product->price, 0) }}</span>
            @if($product->compare_price && $product->compare_price > $product->price)
                <span class="text-lg text-stone-400 line-through">₹{{ number_format($product->compare_price, 0) }}</span>
            @endif
        </div>

        <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-5">
            @csrf
            <button type="submit" class="w-full btn-primary text-sm py-3.5">
                Add to Cart
            </button>
        </form>
    </div>
</article>
