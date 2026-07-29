@extends('layouts.app')

@section('title', 'Our Story — Hillnest')

@section('content')

<section class="about-hero">
    <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=85&auto=format&fit=crop" alt="Himalayan mountains at sunrise" class="about-hero__image">
    <div class="about-hero__overlay" aria-hidden="true"></div>
    <div class="about-hero__content">
        <p class="about-eyebrow">Our Story</p>
        <h1>From Chhajpur<br>to Your Kitchen</h1>
        <p>Two sisters from Upper Shimla, one promise — pure bilona ghee made the way their village always has, shared with families across India.</p>
    </div>
</section>

<section class="about-page">
    <div class="about-shell">

        {{-- Opening narrative --}}
        <div class="about-intro">
            <div class="about-intro__head">
                <p class="about-eyebrow">The HillNest Story</p>
                <h2>Rooted in the Himalayas,<br><em>crafted with purpose</em></h2>
                <div class="about-divider"><span>❧</span></div>
            </div>
            <div class="about-intro__body">
                <p class="about-intro__lead">HillNest is a startup from Upper Shimla — where mornings smell of pine, evenings glow golden on the hills, and apple orchards stretch as far as the eye can see.</p>
                <p>We began with what we know best: <strong>pure bilona cow ghee</strong>. Our cows graze freely on high-altitude meadows. Their milk becomes curd, and curd becomes ghee through the slow wooden churn — the bilona way our grandparents trusted for generations.</p>
                <p>Every jar of HillNest ghee carries that heritage — no palm oil, no shortcuts, no compromise. Just village craft, Himalayan purity, and the honest flavours of home.</p>
            </div>
        </div>

        {{-- Story chapters --}}
        <div class="about-chapters">
            <article class="about-chapter">
                <span class="about-chapter__num">01</span>
                <div class="about-chapter__body">
                    <h3>A Village Upbringing</h3>
                    <p>Sakshi and Meenakshi were born and raised in <strong>Chhajpur</strong> — a small village in Upper Shimla where life moves with the mountains. Mornings begin with crisp, unpolluted air, apple orchards in every direction, and food made the way nature intended — without additives, without compromise.</p>
                    <p>Growing up surrounded by pure milk, home-churned bilona ghee, and the honest flavours of the Himalayas, the sisters learned early what real nourishment feels like.</p>
                </div>
            </article>

            <article class="about-chapter">
                <span class="about-chapter__num">02</span>
                <div class="about-chapter__body">
                    <h3>Seeing the Gap</h3>
                    <p>When they moved beyond Chhajpur, they saw how difficult it had become for families to find truly pure ghee. Shelves were filled with products laced with preservatives, palm oil, and artificial flavours — far from the golden ghee they had known all their lives. That is when they decided to act.</p>
                </div>
            </article>

            <article class="about-chapter">
                <span class="about-chapter__num">03</span>
                <div class="about-chapter__body">
                    <h3>The HillNest Promise</h3>
                    <p class="about-chapter__highlight"><strong>HillNest was born from a simple promise:</strong> to bring the same pure bilona ghee from their village in Chhajpur to kitchens across India — so every family can choose authenticity over adulteration, and taste the Himalayas the way they were meant to be tasted.</p>
                </div>
            </article>
        </div>

        {{-- Bilona process --}}
        <div class="about-process">
            <div class="about-process__head">
                <p class="about-eyebrow">The Bilona Way</p>
                <h2>Five steps from<br><em>village to jar</em></h2>
                <p class="about-process__intro">In local homes across Upper Shimla, families have made pure bilona ghee the same way for generations — no factories, no shortcuts.</p>
            </div>

            <ol class="about-process__steps">
                <li class="about-process__step">
                    <span class="about-process__step-num">01</span>
                    <div>
                        <h4>Fresh A2 Milk</h4>
                        <p>Collected each morning from free-grazing Himalayan cows on high-altitude pastures.</p>
                    </div>
                </li>
                <li class="about-process__step">
                    <span class="about-process__step-num">02</span>
                    <div>
                        <h4>Curd Set Traditionally</h4>
                        <p>Milk cultured overnight in earthen pots — the way it has always been done here.</p>
                    </div>
                </li>
                <li class="about-process__step">
                    <span class="about-process__step-num">03</span>
                    <div>
                        <h4>Hand-Churned Bilona</h4>
                        <p>Curd churned in wooden bilona until rich butter separates from buttermilk.</p>
                    </div>
                </li>
                <li class="about-process__step">
                    <span class="about-process__step-num">04</span>
                    <div>
                        <h4>Slow-Cooked Over Wood Fire</h4>
                        <p>Butter clarified slowly in small batches, releasing its golden colour and nutty aroma.</p>
                    </div>
                </li>
                <li class="about-process__step">
                    <span class="about-process__step-num">05</span>
                    <div>
                        <h4>Packed in Our Village</h4>
                        <p>Every jar sealed by hand before it leaves the Himalayas for your kitchen.</p>
                    </div>
                </li>
            </ol>
        </div>

        {{-- Apple orchard --}}
        <div class="about-apple">
            <div class="about-apple__visual">
                <div class="about-apple__farm-frame">
                    <img
                        src="{{ asset('images/apple-farm.jpg') }}?v={{ @filemtime(public_path('images/apple-farm.jpg')) }}"
                        alt="HillNest apple orchard in Upper Shimla with ripe red apples"
                        class="about-apple__farm-image"
                        loading="lazy"
                        decoding="async"
                    >
                </div>
                <span class="about-apple__badge">Coming Soon</span>
            </div>

            <div class="about-apple__content">
                <p class="about-eyebrow">Beyond Ghee</p>
                <h2>Shimla Apples</h2>
                <p class="about-apple__lead">We tend an apple orchard in the hills of Chhajpur — the same village where HillNest was born. Soon, we will bring you crisp, orchard-fresh Shimla apples — another gift from these mountains.</p>

                <ul class="about-apple__features">
                    <li>
                        <strong>Orchard-Grown</strong>
                        <span>From our own trees in Upper Shimla</span>
                    </li>
                    <li>
                        <strong>Mountain Fresh</strong>
                        <span>Picked at peak ripeness, packed with care</span>
                    </li>
                    <li>
                        <strong>Pure & Natural</strong>
                        <span>No wax, no artificial ripening</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Values --}}
        <ul class="about-values" aria-label="What HillNest stands for">
            <li>
                <strong>Pure Bilona Ghee</strong>
                <span>Village-made, hand-churned tradition</span>
            </li>
            <li>
                <strong>Zero Additives</strong>
                <span>No preservatives or artificial flavours</span>
            </li>
            <li>
                <strong>Chhajpur to Your Home</strong>
                <span>From Upper Shimla straight to your kitchen</span>
            </li>
        </ul>

        <div class="about-location">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
            <p>From the apple orchards and pure mountain air of <strong>Chhajpur, Upper Shimla</strong> — Village Dharmana, P.O. Anti, Tehsil Jubbal, Distt. Shimla, Himachal Pradesh.</p>
        </div>

        <div class="about-cta">
            <a href="{{ route('shop.index') }}" class="btn-primary"><span>Shop Our Ghee</span></a>
            <a href="{{ route('home') }}#our-story" class="btn-ghost">See the Bilona Process →</a>
        </div>

    </div>
</section>
@endsection
