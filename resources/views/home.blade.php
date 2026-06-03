@extends('layouts.app')

@section('title', 'Hillnest | Pure Bilona Cow Ghee from Upper Shimla')

@section('content')
@php $slides = hillnest_hero_slides(); @endphp

{{-- Hero slider (Rosier-style) --}}
<section id="hero-slider" class="relative h-[420px] sm:h-[500px] md:h-[580px] lg:h-[640px] overflow-hidden bg-brand">
    @foreach($slides as $i => $slide)
    <div class="hero-slide absolute inset-0 {{ $i === 0 ? 'active' : '' }}">
        <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-brand/85 via-brand/50 to-transparent"></div>
        <div class="relative mx-auto flex h-full max-w-[1400px] items-center px-4 sm:px-6 lg:px-8">
            <div class="max-w-xl text-white">
                <p class="text-sm md:text-base font-semibold uppercase tracking-[0.25em] text-gold-light mb-4">Upper Shimla · Himachal Pradesh</p>
                <h1 class="font-display text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-semibold leading-[1.1]">{{ $slide['title'] }}</h1>
                <p class="mt-5 text-lg md:text-xl text-cream/90 leading-relaxed max-w-lg">{{ $slide['subtitle'] }}</p>
                <a href="{{ route('shop.index') }}" class="mt-8 inline-block btn-gold">Shop Now</a>
            </div>
        </div>
    </div>
    @endforeach

    <button type="button" data-hero-prev class="absolute left-4 top-1/2 -translate-y-1/2 z-10 flex h-12 w-12 items-center justify-center rounded-full bg-white/20 text-white backdrop-blur hover:bg-white/40 transition text-2xl">‹</button>
    <button type="button" data-hero-next class="absolute right-4 top-1/2 -translate-y-1/2 z-10 flex h-12 w-12 items-center justify-center rounded-full bg-white/20 text-white backdrop-blur hover:bg-white/40 transition text-2xl">›</button>

    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-10 flex gap-2">
        @foreach($slides as $i => $slide)
        <button type="button" data-hero-dot class="h-2.5 w-2.5 rounded-full transition {{ $i === 0 ? 'bg-gold w-8' : 'bg-white/50' }}" aria-label="Slide {{ $i + 1 }}"></button>
        @endforeach
    </div>
</section>

{{-- Category pills --}}
<section class="border-b border-hill-200 bg-white py-5">
    <div class="mx-auto max-w-[1400px] px-4 sm:px-6">
        <div class="flex gap-3 overflow-x-auto scrollbar-hide justify-start md:justify-center">
            <a href="{{ route('shop.index') }}" class="shrink-0 rounded-full bg-brand px-6 py-2.5 text-base font-semibold text-white">Bilona Ghee</a>
            <span class="shrink-0 rounded-full border border-hill-200 bg-cream px-6 py-2.5 text-base font-medium text-brand-light">Shimla Apples <span class="text-gold text-sm">Soon</span></span>
            <a href="{{ route('about') }}" class="shrink-0 rounded-full border border-hill-200 px-6 py-2.5 text-base font-medium text-brand hover:border-gold hover:text-gold transition">Our Story</a>
        </div>
    </div>
</section>

{{-- Loved Across Generations --}}
@if($featuredProducts->count())
<section id="collection" class="py-14 md:py-20 bg-cream">
    <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8">
        <h2 class="section-title">Loved Across Generations</h2>
        <p class="text-center mt-4 text-lg text-brand-light max-w-2xl mx-auto">Pure bilona cow ghee — hand-churned, small-batch, straight from upper Shimla.</p>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($featuredProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('shop.index') }}" class="btn-outline">View All Products</a>
        </div>
    </div>
</section>
@endif

{{-- Brand promise (Rosier: Foods that heal) --}}
<section class="py-14 md:py-20 bg-white border-y border-hill-200">
    <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-sm font-bold uppercase tracking-[0.3em] text-gold mb-4">Foods That Heal, Not Hype</p>
        <h2 class="font-display text-3xl md:text-5xl font-semibold text-brand">Pure · Traditional · Ethically Crafted</h2>
    </div>
</section>

{{-- Experience grid with Unsplash images --}}
<section class="py-14 md:py-20 bg-cream">
    <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="grid grid-cols-2 gap-4">
                <img src="https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=600&q=80&auto=format&fit=crop" alt="Fresh milk" class="rounded-sm h-48 w-full object-cover">
                <img src="https://images.unsplash.com/photo-1589985278721-4afeaa8bce84?w=600&q=80&auto=format&fit=crop" alt="Golden ghee" class="rounded-sm h-48 w-full object-cover mt-8">
                <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&q=80&auto=format&fit=crop" alt="Himalayas" class="rounded-sm h-48 w-full object-cover col-span-2">
            </div>
            <div>
                <h2 class="font-display text-3xl md:text-4xl font-semibold text-brand">The Hillnest Experience</h2>
                <p class="mt-2 text-lg text-gold font-medium">असली स्वाद की एक सच्ची यात्रा</p>
                <ul class="mt-10 space-y-8">
                    @foreach([
                        ['title' => 'Source To Table', 'desc' => 'Our own cows in upper Shimla — meadow-grazed, cared for with respect.'],
                        ['title' => 'Time-Honored Bilona', 'desc' => 'Slow wooden churn from curd — the method our hills have trusted for generations.'],
                        ['title' => 'Unwavering Purity', 'desc' => 'No palm oil, no additives — only golden, grainy, aromatic ghee.'],
                        ['title' => 'Himalayan Promise', 'desc' => 'Soon, apples from our Shimla orchard join the Hillnest family.'],
                    ] as $item)
                    <li>
                        <h3 class="text-xl font-bold text-brand">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-base text-brand-light leading-relaxed">{{ $item['desc'] }}</p>
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('about') }}" class="mt-10 inline-block btn-primary">Explore Our Heritage</a>
            </div>
        </div>
    </div>
</section>

{{-- Testimonials --}}
<section class="py-14 md:py-20 bg-white">
    <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8">
        <h2 class="section-title">More Than a Brand, A Family</h2>
        <p class="text-center mt-3 text-lg text-brand-light">जड़ों से जुड़े लोग, असली बदलाव</p>

        <div class="mt-12 grid gap-6 md:grid-cols-3">
            @foreach([
                ['name' => 'Priya Sharma', 'text' => 'The aroma reminds me of my grandmother\'s kitchen in the hills. Pure, rich, and worth every rupee.', 'product' => 'Pure Bilona Ghee — 500g'],
                ['name' => 'Rahul Verma', 'text' => 'Finally ghee that feels real — grainy texture, golden colour, no oily aftertaste. Hillnest is our family brand now.', 'product' => 'Pure Bilona Ghee — 1kg'],
                ['name' => 'Anita Thakur', 'text' => 'COD delivery was smooth. Packaging was secure. The 2kg tin is perfect for our joint family in Shimla.', 'product' => 'Pure Bilona Ghee — 2kg'],
            ] as $review)
            <blockquote class="border border-hill-200 bg-cream p-8 text-center">
                <p class="text-base md:text-lg text-brand leading-relaxed italic">"{{ $review['text'] }}"</p>
                <footer class="mt-6">
                    <p class="font-bold text-brand text-lg">{{ $review['name'] }}</p>
                    <p class="text-sm text-gold mt-1">{{ $review['product'] }}</p>
                </footer>
            </blockquote>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="relative py-20 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1628088062856-eee32a9352e2?w=1920&q=80&auto=format&fit=crop" alt="" class="absolute inset-0 h-full w-full object-cover">
    <div class="absolute inset-0 bg-brand/80"></div>
    <div class="relative mx-auto max-w-3xl px-4 text-center text-white">
        <h2 class="font-display text-3xl md:text-5xl font-semibold">Taste the Himalayas at Home</h2>
        <p class="mt-5 text-lg md:text-xl text-cream/90">Free shipping above ₹2,000 · Cash on Delivery across India</p>
        <a href="{{ route('shop.index') }}" class="mt-10 inline-block btn-gold">Order Pure Ghee</a>
    </div>
</section>
@endsection
