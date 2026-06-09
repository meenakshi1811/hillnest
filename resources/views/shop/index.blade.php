@extends('layouts.app')

@section('title', 'Shop Pure Bilona Ghee — Hillnest')

@section('content')
<section class="shop-page-hero" id="collection">
    <div class="shop-shell shop-page-hero__inner">
        <p class="shop-eyebrow">HillNest Collection</p>
        <h1>Shop Ghee</h1>
        <p>Pure bilona cow ghee from upper Shimla — every size, same Himalayan purity.</p>
    </div>
</section>

<section class="shop-products-section">
    <div class="shop-shell">
        @if($products->count())
            <div class="shop-products-grid">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @else
            <div class="shop-empty-state">
                <p class="shop-eyebrow">Coming soon</p>
                <h2>Products are being prepared</h2>
                <p>Our pure A2 bilona ghee collection will be available here shortly.</p>
            </div>
        @endif
    </div>
</section>
@endsection
