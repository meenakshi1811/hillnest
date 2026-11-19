@extends('layouts.app')

@section('title', $post->seo_title)
@section('meta_description', $post->seo_description)
@section('og_type', 'article')
@if($post->cover_image_url)
    @section('og_image', $post->cover_image_url)
@endif
@unless($post->isLive())
    @section('robots', 'noindex')
@endunless

@push('structured_data')
@php
    $store = config('hillnest.store');
    $publisher = [
        '@type' => 'Organization',
        '@id' => url('/').'#organization',
        'name' => $store['name'],
        'url' => url('/'),
        'logo' => ['@type' => 'ImageObject', 'url' => asset('images/logo.png')],
    ];
    $articleLd = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => route('blog.show', $post)],
        'headline' => Str::limit($post->title, 110, ''),
        'description' => $post->seo_description,
        'image' => [$post->cover_image_url ?: asset('images/og-image.jpg')],
        'datePublished' => $post->published_at?->toAtomString(),
        'dateModified' => $post->updated_at->toAtomString(),
        'author' => $post->author_name === $store['name']
            ? ['@id' => url('/').'#organization']
            : ['@type' => 'Person', 'name' => $post->author_name, 'url' => route('about')],
        'publisher' => $publisher,
        'articleSection' => $post->category,
        'inLanguage' => 'en-IN',
    ]);
    $breadcrumbLd = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('blog.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $post->title, 'item' => route('blog.show', $post)],
        ],
    ];
    $faqLd = $post->faq_items ? [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(fn ($faq) => [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
        ], $post->faq_items),
    ] : null;
@endphp
@if($post->published_at)
<meta property="article:published_time" content="{{ $post->published_at->toAtomString() }}" />
@endif
<meta property="article:modified_time" content="{{ $post->updated_at->toAtomString() }}" />
<script type="application/ld+json">
@json($articleLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG)
</script>
<script type="application/ld+json">
@json($breadcrumbLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG)
</script>
@if($faqLd)
<script type="application/ld+json">
@json($faqLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG)
</script>
@endif
@endpush

@section('content')
@unless($post->isLive())
    <div class="blog-preview-banner" role="status">
        Admin preview — this article is {{ $post->is_published && $post->published_at ? 'scheduled for '.$post->published_at->timezone('Asia/Kolkata')->format('j M Y, h:i A') : 'a draft' }} and hidden from visitors and Google.
        <a href="{{ route('admin.blog.edit', $post) }}">Edit</a>
    </div>
@endunless

<article class="blog-article">
    <header class="blog-article__header">
        <div class="blog-shell">
            <nav class="blog-breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">›</span>
                <a href="{{ route('blog.index') }}">Blog</a>
                @if($post->category)
                    <span aria-hidden="true">›</span>
                    <span>{{ $post->category }}</span>
                @endif
            </nav>
            <h1>{{ $post->title }}</h1>
            <p class="blog-article__lead">{{ $post->excerpt }}</p>
            <p class="blog-article__meta">
                By <a href="{{ route('about') }}">{{ $post->author_name }}</a>
                @if($post->published_at)
                    · <time datetime="{{ $post->published_at->toAtomString() }}">{{ $post->published_at->timezone('Asia/Kolkata')->format('j F Y') }}</time>
                @endif
                @if($post->published_at && $post->updated_at->gt($post->published_at->copy()->addDay()))
                    · Updated <time datetime="{{ $post->updated_at->toAtomString() }}">{{ $post->updated_at->timezone('Asia/Kolkata')->format('j F Y') }}</time>
                @endif
                · {{ $post->reading_minutes }} min read
            </p>
        </div>
    </header>

    @if($post->cover_image_url)
        <figure class="blog-article__cover">
            @php($coverSize = $post->cover_image && is_file(public_path($post->cover_image)) ? @getimagesize(public_path($post->cover_image)) : null)
            <img src="{{ $post->cover_image_url }}" alt="{{ $post->cover_image_alt ?: $post->title }}" @if($coverSize) width="{{ $coverSize[0] }}" height="{{ $coverSize[1] }}" @endif fetchpriority="high" decoding="async">
        </figure>
    @endif

    <div class="blog-article__body">
    <div class="blog-shell">
        @if(count($content['toc']) >= 3)
            <nav class="blog-toc" aria-label="In this article">
                <p class="blog-toc__title">In this article</p>
                <ol>
                    @foreach($content['toc'] as $item)
                        <li><a href="#{{ $item['id'] }}">{{ $item['text'] }}</a></li>
                    @endforeach
                </ol>
            </nav>
        @endif

        <div class="blog-prose">
            {!! $content['html'] !!}
        </div>

        @if($post->faq_items)
            <section class="blog-faq" aria-labelledby="blog-faq-title">
                <h2 id="blog-faq-title">Frequently asked questions</h2>
                @foreach($post->faq_items as $faq)
                    <details class="blog-faq__item" @if($loop->first) open @endif>
                        <summary>{{ $faq['question'] }}</summary>
                        <p>{{ $faq['answer'] }}</p>
                    </details>
                @endforeach
            </section>
        @endif

        <aside class="blog-author">
            <p class="blog-author__title">Written by {{ $post->author_name }}</p>
            <p>HillNest is run by two sisters from Chhajpur in Upper Shimla. Our ghee is made the bilona way in village homes — curd churned by hand and slowly clarified over a wood fire. <a href="{{ route('about') }}">Read our story</a>.</p>
        </aside>
    </div>
    </div>
</article>

@if($products->count())
<section class="blog-products">
    <div class="shop-shell">
        <div class="product-detail-related__header">
            <p class="shop-eyebrow">From our village kitchen</p>
            <h2 class="section-title">Taste pure <em>bilona ghee</em></h2>
        </div>
        <div class="shop-products-grid home-products-grid">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>
@endif

@if($related->count())
<section class="blog-related">
    <div class="shop-shell">
        <div class="product-detail-related__header">
            <p class="shop-eyebrow">Keep reading</p>
            <h2 class="section-title">More from the <em>journal</em></h2>
        </div>
        <div class="blog-grid">
            @foreach($related as $item)
                @include('blog.partials.card', ['post' => $item])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
