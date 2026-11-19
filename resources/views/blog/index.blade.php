@extends('layouts.app')

@section('title', $posts->currentPage() > 1 ? 'Ghee Guides & Recipes — Page '.$posts->currentPage().' — HillNest Blog' : 'Ghee Guides, Recipes & Village Stories — HillNest Blog')
@section('meta_description', 'Learn how pure bilona ghee is made, how to check ghee purity at home, and how to cook with it — guides and recipes from our village in Upper Shimla.')
@if($posts->currentPage() > 1)
    @section('canonical', rtrim(config('app.url'), '/').'/blog?page='.$posts->currentPage())
@endif

@push('structured_data')
@php
    $breadcrumbLd = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('blog.index')],
        ],
    ];
@endphp
<script type="application/ld+json">
@json($breadcrumbLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG)
</script>
@endpush

@section('content')
<section class="blog-hero">
    <div class="shop-shell">
        <p class="shop-eyebrow">The HillNest Journal</p>
        <h1>Ghee guides, recipes <em>&amp; village stories</em></h1>
        <p class="blog-hero__lead">How bilona ghee is made, how to tell pure ghee from adulterated ghee, and the dishes we cook with it at home in Upper Shimla.</p>
    </div>
</section>

<section class="blog-list">
    <div class="shop-shell">
        @if($posts->isEmpty())
            <div class="blog-empty">
                <p>Our first articles are on their way. Meanwhile, read <a href="{{ route('about') }}">our story</a> or <a href="{{ route('shop.index') }}">visit the shop</a>.</p>
            </div>
        @else
            <div class="blog-grid">
                @foreach($posts as $post)
                    @include('blog.partials.card', ['post' => $post, 'eager' => $loop->first && $posts->currentPage() === 1])
                @endforeach
            </div>

            @if($posts->hasPages())
                <div class="blog-pagination">
                    {{ $posts->links() }}
                </div>
            @endif
        @endif
    </div>
</section>
@endsection
