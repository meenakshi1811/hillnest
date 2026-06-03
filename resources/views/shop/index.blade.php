@extends('layouts.app')

@section('title', 'Shop Pure Bilona Ghee — Hillnest')

@section('content')
<section class="bg-white border-b border-hill-200 py-10 md:py-14">
    <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-display text-4xl md:text-5xl font-semibold text-brand">Shop Ghee</h1>
        <p class="mt-4 text-lg md:text-xl text-brand-light max-w-2xl mx-auto">Pure bilona cow ghee from upper Shimla — every size, same Himalayan purity.</p>
    </div>
</section>

<section class="py-12 md:py-16 bg-cream">
    <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8">
        @if($products->count())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @else
            <p class="text-center text-lg text-brand-light py-20">Products coming soon.</p>
        @endif
    </div>
</section>
@endsection
