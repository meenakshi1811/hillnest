@extends('layouts.app')

@section('title', $product->name . ' — Hillnest')

@section('content')
<section class="bg-white border-b border-hill-200 py-6">
    <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8">
        <nav class="text-base text-brand-light">
            <a href="{{ route('home') }}" class="hover:text-gold">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('shop.index') }}" class="hover:text-gold">Shop</a>
            <span class="mx-2">/</span>
            <span class="text-brand">{{ $product->name }}</span>
        </nav>
    </div>
</section>

<section class="py-10 md:py-16 bg-cream">
    <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="bg-white border border-hill-200 overflow-hidden">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full aspect-square object-cover">
            </div>

            <div class="lg:pt-4">
                @if($product->badge)
                    <span class="inline-block bg-gold text-white text-xs font-bold uppercase tracking-wider px-3 py-1 mb-4">{{ $product->badge }}</span>
                @endif
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-gold">{{ $product->size }} · Bilona Ghee</p>
                <h1 class="font-display mt-3 text-3xl md:text-4xl lg:text-5xl font-semibold text-brand leading-tight">{{ $product->name }}</h1>

                @if($product->reviews_count > 0)
                <div class="mt-4 flex items-center gap-2 text-base text-brand-light">
                    <span class="text-gold text-lg">★★★★★</span>
                    <span>{{ number_format($product->reviews_count) }} reviews</span>
                </div>
                @endif

                <p class="mt-5 text-lg text-brand-light leading-relaxed">{{ $product->short_description }}</p>

                <div class="mt-8 flex items-baseline gap-4">
                    <span class="text-4xl font-bold text-brand">₹{{ number_format($product->price, 0) }}</span>
                    @if($product->compare_price && $product->compare_price > $product->price)
                        <span class="text-2xl text-stone-400 line-through">₹{{ number_format($product->compare_price, 0) }}</span>
                        <span class="bg-red-100 text-red-700 text-sm font-bold px-3 py-1">Save ₹{{ number_format($product->compare_price - $product->price, 0) }}</span>
                    @endif
                </div>

                <p class="mt-3 text-base font-medium {{ $product->stock > 0 ? 'text-emerald-700' : 'text-red-600' }}">
                    {{ $product->stock > 0 ? '✓ In stock — ships from upper Shimla' : 'Out of stock' }}
                </p>

                @if($product->stock > 0)
                <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-8 flex flex-wrap items-stretch gap-4">
                    @csrf
                    <div class="flex items-center border-2 border-hill-200 bg-white">
                        <label class="sr-only">Quantity</label>
                        <input type="number" name="quantity" value="1" min="1" max="20" class="w-20 py-4 text-center text-lg font-semibold text-brand border-0 focus:ring-0 outline-none">
                    </div>
                    <button type="submit" class="flex-1 min-w-[200px] btn-primary py-4">Add to Cart</button>
                </form>
                @endif

                <div class="mt-10 border-t border-hill-200 pt-10">
                    <h2 class="font-display text-2xl font-semibold text-brand mb-4">Description</h2>
                    <p class="text-base md:text-lg text-brand-light leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
                </div>
            </div>
        </div>

        @if($related->count())
        <div class="mt-20 md:mt-28">
            <h2 class="section-title">You May Also Like</h2>
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($related as $item)
                    <x-product-card :product="$item" />
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
