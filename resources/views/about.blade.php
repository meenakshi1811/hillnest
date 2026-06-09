@extends('layouts.app')

@section('title', 'Our Story — Hillnest')

@section('content')
<section class="about-hero">
    <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=85&auto=format&fit=crop" alt="Himalayas" class="about-hero__image">
    <div class="about-hero__overlay" aria-hidden="true"></div>
    <div class="about-hero__content">
        <p class="about-eyebrow">Our Story</p>
        <h1>Rooted in Upper Shimla</h1>
        <p>Small-batch bilona ghee shaped by mountain mornings, patient craft, and Himalayan abundance.</p>
    </div>
</section>

<section class="about-story-section">
    <div class="about-shell">
        <div class="about-story-card">
            <div class="about-story-card__intro">
                <p class="about-eyebrow">HillNest Heritage</p>
                <h2>From pine-scented mornings to golden jars of ghee.</h2>
            </div>
            <div class="about-story-card__copy">
                <p>Hillnest is a startup from upper Shimla — where mornings smell of pine, and evenings glow golden on the hills.</p>
                <p>We began with what we know best: <strong>pure bilona cow ghee</strong>. Our cows graze freely. Their milk becomes curd, and curd becomes ghee through the slow wooden churn — the bilona way our grandparents trusted.</p>
                <p>Every jar of Hillnest ghee carries that heritage — no palm oil, no shortcuts, no compromise.</p>
            </div>
        </div>

        <div class="about-orchard-card">
            <div class="about-orchard-card__image-wrap">
                <img src="https://images.unsplash.com/photo-1560807707-8cc77767d783?w=900&q=80&auto=format&fit=crop" alt="Apple orchard" class="about-orchard-card__image">
            </div>
            <div class="about-orchard-card__content">
                <p class="about-eyebrow">Coming Soon</p>
                <h2>Shimla Apples</h2>
                <p>We tend an apple orchard in Shimla. Soon, Hillnest will bring you crisp, orchard-fresh apples — another gift from these mountains.</p>
            </div>
        </div>

        <div class="about-cta">
            <a href="{{ route('shop.index') }}" class="btn-primary"><span>Shop Our Ghee</span></a>
        </div>
    </div>
</section>
@endsection
