@extends('layouts.app')

@section('title', 'Our Story — Hillnest')

@section('content')
<section class="relative h-[320px] md:h-[400px] overflow-hidden">
    <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=85&auto=format&fit=crop" alt="Himalayas" class="absolute inset-0 h-full w-full object-cover">
    <div class="absolute inset-0 bg-brand/70"></div>
    <div class="relative flex h-full items-center justify-center text-center px-4">
        <div>
            <h1 class="font-display text-4xl md:text-6xl font-semibold text-white">Our Story</h1>
            <p class="mt-4 text-lg md:text-xl text-cream/90">Rooted in upper Shimla. Growing with the Himalayas.</p>
        </div>
    </div>
</section>

<section class="py-14 md:py-20 bg-cream">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 text-center md:text-left">
        <p class="text-xl md:text-2xl text-brand font-medium leading-relaxed">Hillnest is a startup from upper Shimla — where mornings smell of pine, and evenings glow golden on the hills.</p>
        <div class="mt-10 space-y-6 text-base md:text-lg text-brand-light leading-relaxed">
            <p>We began with what we know best: <strong class="text-brand">pure bilona cow ghee</strong>. Our cows graze freely. Their milk becomes curd, and curd becomes ghee through the slow wooden churn — the bilona way our grandparents trusted.</p>
            <p>Every jar of Hillnest ghee carries that heritage — no palm oil, no shortcuts, no compromise.</p>
        </div>
        <div class="mt-12 rounded-sm border border-hill-200 bg-white p-8 md:p-10 text-center">
            <img src="https://images.unsplash.com/photo-1560807707-8cc77767d783?w=600&q=80&auto=format&fit=crop" alt="Apple orchard" class="mx-auto h-48 w-full max-w-md object-cover rounded-sm mb-6">
            <h2 class="font-display text-2xl md:text-3xl font-semibold text-brand">Coming Soon: Shimla Apples</h2>
            <p class="mt-4 text-base md:text-lg text-brand-light">We tend an apple orchard in Shimla. Soon, Hillnest will bring you crisp, orchard-fresh apples — another gift from these mountains.</p>
        </div>
        <div class="mt-12 text-center">
            <a href="{{ route('shop.index') }}" class="btn-primary">Shop Our Ghee</a>
        </div>
    </div>
</section>
@endsection
