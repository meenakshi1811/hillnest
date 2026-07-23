@extends('layouts.app')

@section('title', 'Homepage — Hillnest')

@section('content')

<!-- HERO -->
<section class="hero">
  <div class="hero-bg-pattern" aria-hidden="true"></div>
  <div class="hero-inner">
    <div class="hero-content">
      <div class="hero-eyebrow">
        <span class="eyebrow-line"></span>
        <span class="eyebrow-text">From Upper Shimla, Himalayas</span>
      </div>
      <h1>Pure A2 <em>Bilona</em><br>Ghee From<br>Upper Shimla</h1>
      <p class="hero-tagline">Nurtured with Nature, Made with Love</p>
      <div class="hero-features" aria-label="HillNest purity promises">
        <div class="hero-feature-row">
          <span class="feature-dot"></span>
          Traditional Bilona small-batch process
        </div>
        <div class="hero-feature-row">
          <span class="feature-dot"></span>
          Pure A2 cow ghee with no preservatives
        </div>
        <div class="hero-feature-row">
          <span class="feature-dot"></span>
          Sourced from Upper Shimla Himalayan farms
        </div>
      </div>
      <div class="hero-actions">
        <a href="{{ route('shop.index') }}" class="btn-primary"><span>Shop Now</span></a>
        <a href="{{ route('about') }}" class="btn-ghost">Our Story →</a>
      </div>
    </div>

    <div class="hero-visual">
      <div class="hero-circle-bg" aria-hidden="true"></div>
      <div class="hero-jar-container">
        <div class="hero-jar">
          <img
            src="{{ asset('images/homepage_image.png') }}?v={{ @filemtime(public_path('images/homepage_image.png')) }}"
            alt="HillNest Pure A2 Bilona Ghee — 250g, 500g and 1kg jars"
            width="900"
            height="900"
            decoding="async"
            fetchpriority="high"
          />
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Strip -->
<div class="strip">
  <div class="strip-track" id="strip">
    <div class="strip-item"><span class="strip-dot"></span>Pure Organic Ghee</div>
    <div class="strip-item"><span class="strip-dot"></span>Bilona Method</div>
    <div class="strip-item"><span class="strip-dot"></span>Upper Shimla</div>
    <div class="strip-item"><span class="strip-dot"></span>A2 Cow Milk</div>
    <div class="strip-item"><span class="strip-dot"></span>Small Batch Crafted</div>
    <div class="strip-item"><span class="strip-dot"></span>No Preservatives</div>
    <div class="strip-item"><span class="strip-dot"></span>Himalayan Purity</div>
    <div class="strip-item"><span class="strip-dot"></span>Nurtured by Nature</div>
    <div class="strip-item"><span class="strip-dot"></span>Pure Organic Ghee</div>
    <div class="strip-item"><span class="strip-dot"></span>Bilona Method</div>
    <div class="strip-item"><span class="strip-dot"></span>Upper Shimla</div>
    <div class="strip-item"><span class="strip-dot"></span>A2 Cow Milk</div>
    <div class="strip-item"><span class="strip-dot"></span>Small Batch Crafted</div>
    <div class="strip-item"><span class="strip-dot"></span>No Preservatives</div>
    <div class="strip-item"><span class="strip-dot"></span>Himalayan Purity</div>
    <div class="strip-item"><span class="strip-dot"></span>Nurtured by Nature</div>
  </div>
</div>

<!-- Products -->
<section id="collection" class="collection-section">
  <div class="section collection-section__inner">
    <div class="section-header collection-section__header">
      <span class="section-eyebrow">Our Collection</span>
      <h2 class="section-title">The <em>Finest</em> Himalayan Ghee</h2>
      <p class="section-subtitle">Each variety lovingly crafted from the milk of free-grazing cows, slow-cooked to golden perfection.</p>
      <div class="divider-ornament"><span>❧</span></div>
      <ul class="collection-trust" aria-label="Product quality highlights">
        <li>Pure A2 Cow</li>
        <li>Traditional Bilona</li>
        <li>No Preservatives</li>
      </ul>
    </div>

    @if($featuredProducts->count())
      <div class="collection-showcase">
        <div class="shop-products-grid home-products-grid collection-grid">
          @foreach($featuredProducts as $product)
            <x-product-card :product="$product" />
          @endforeach
        </div>
      </div>
      <div class="collection-section__footer">
        <a href="{{ route('shop.index') }}" class="btn-collection-view">View All Products →</a>
      </div>
    @else
      <p class="section-subtitle collection-section__empty">Our ghee collection is coming soon.</p>
    @endif
  </div>
</section>

<!-- Why HillNest — Village Origin -->
<section class="origin-section" id="our-story">
  <div class="origin-section__glow" aria-hidden="true"></div>

  <div class="origin-inner">
    <div class="origin-header">
      <span class="section-eyebrow">From Upper Shimla</span>
      <h2 class="section-title">Made in Local Homes,<br><em>Crafted with Purity</em></h2>
      <div class="divider-ornament divider-ornament--light"><span>❧</span></div>
      <p class="origin-intro">
        In a small village nestled in Upper Shimla, families have been making pure bilona ghee the same way for generations. No factories, no shortcuts — just local homes, free-grazing A2 cows, and a process rooted in Himalayan tradition.
      </p>
    </div>

    <ol class="origin-steps">
      <li class="origin-step">
        <div class="origin-step__marker">
          <span class="origin-step__num">01</span>
        </div>
        <div class="origin-step__card">
          <h3 class="origin-step__title">Fresh Milk from Village Homes</h3>
          <p class="origin-step__desc">Each morning, A2 milk is collected from the kitchens and small farms of families across our village. The same homes that raise their cows with care, feeding them on natural high-altitude pastures — pure milk, straight from the source.</p>
        </div>
      </li>

      <li class="origin-step">
        <div class="origin-step__marker">
          <span class="origin-step__num">02</span>
        </div>
        <div class="origin-step__card">
          <h3 class="origin-step__title">Curd Set the Traditional Way</h3>
          <p class="origin-step__desc">The milk is gently warmed and cultured overnight in earthen pots inside local homes — the way it has always been done here. No machines, no additives. Just time, warmth, and the natural cultures passed down through generations.</p>
        </div>
      </li>

      <li class="origin-step">
        <div class="origin-step__marker">
          <span class="origin-step__num">03</span>
        </div>
        <div class="origin-step__card">
          <h3 class="origin-step__title">Hand-Churned in Wooden Bilona</h3>
          <p class="origin-step__desc">Village women hand-churn the curd in traditional wooden bilona churners until rich butter separates from buttermilk. It is slow, patient work — the kind that cannot be rushed, and that is exactly what makes the ghee so pure.</p>
        </div>
      </li>

      <li class="origin-step">
        <div class="origin-step__marker">
          <span class="origin-step__num">04</span>
        </div>
        <div class="origin-step__card">
          <h3 class="origin-step__title">Slow-Cooked Over Wood Fire</h3>
          <p class="origin-step__desc">The butter is clarified slowly over a wood fire in small batches, releasing its golden colour and nutty aroma. This is where ghee is truly born — through fire, patience, and the hands of people who know every step by heart.</p>
        </div>
      </li>

      <li class="origin-step">
        <div class="origin-step__marker">
          <span class="origin-step__num">05</span>
        </div>
        <div class="origin-step__card">
          <h3 class="origin-step__title">Packed & Sealed in Our Village</h3>
          <p class="origin-step__desc">Every jar is strained, cooled, and sealed by hand in our village workshop before it leaves the Himalayas. From a local home in Upper Shimla to your kitchen — pure ghee, made with love and nothing else.</p>
        </div>
      </li>
    </ol>

    <ul class="origin-trust" aria-label="Our purity promises">
      <li>
        <strong>Pure A2 Cow Milk</strong>
        <span>From free-grazing Himalayan cows</span>
      </li>
      <li>
        <strong>Village Crafted</strong>
        <span>Made by local families, not factories</span>
      </li>
      <li>
        <strong>Zero Additives</strong>
        <span>No preservatives or artificial flavours</span>
      </li>
      <li>
        <strong>8,000 ft Altitude</strong>
        <span>Upper Shimla's pristine air & water</span>
      </li>
    </ul>
  </div>
</section>

<!-- Founders — How We Started -->
<section class="founders-section" id="how-we-started">
  <div class="founders-section__glow" aria-hidden="true"></div>

  <div class="founders-inner">
    <header class="founders-header section-header">
      <span class="section-eyebrow">How We Started</span>
      <h2 class="section-title">Two Sisters,<br><em>One Pure Mission</em></h2>
      <p class="section-subtitle">From the apple orchards of Chhajpur to kitchens across India — the story behind HillNest.</p>
      <div class="divider-ornament"><span>❧</span></div>
    </header>

    <div class="founders-gallery">
      <div class="founders-gallery__stage">
        <div class="founders-gallery__backdrop" aria-hidden="true"></div>
        <div class="founders-gallery__ambient" aria-hidden="true"></div>

        <div class="founders-duo">
          <article class="founder-card">
            <div class="founder-card__media">
              <img src="{{ asset('images/founders/sakshi.jpeg') }}" alt="Sakshi Nanta — Founder of HillNest" loading="lazy" decoding="async">
            </div>
            <div class="founder-card__meta">
              <h3 class="founder-card__name">Sakshi Nanta</h3>
              <p class="founder-card__role">Founder</p>
            </div>
          </article>

          <div class="founders-duo__join" aria-hidden="true">
            <span class="founders-duo__line"></span>
            <span class="founders-duo__mark">&amp;</span>
            <span class="founders-duo__line"></span>
          </div>

          <article class="founder-card">
            <div class="founder-card__media">
              <img src="{{ asset('images/founders/meenakshi.jpeg') }}" alt="Meenakshi Nanta — Co-founder of HillNest" loading="lazy" decoding="async">
            </div>
            <div class="founder-card__meta">
              <h3 class="founder-card__name">Meenakshi Nanta</h3>
              <p class="founder-card__role">Co-Founder</p>
            </div>
          </article>
        </div>
      </div>

      <div class="founders-gallery__voices">
        <blockquote class="founder-voice">
          <p>We grew up eating ghee made in our own kitchen. I wanted every family to experience that same purity — nothing added, nothing taken away.</p>
          <cite>— Sakshi Nanta</cite>
        </blockquote>

        <blockquote class="founder-voice">
          <p>Chhajpur taught us the value of clean food and clean air. HillNest is our way of sharing that Himalayan goodness with people who deserve better.</p>
          <cite>— Meenakshi Nanta</cite>
        </blockquote>
      </div>
    </div>

    <div class="founders-editorial">
      <div class="founders-editorial__head">
        <span class="founders-editorial__label">Our Story</span>
        <h3 class="founders-editorial__title">Rooted in the Himalayas</h3>
      </div>

      <div class="founders-chapters">
        <article class="founders-chapter">
          <span class="founders-chapter__num">01</span>
          <div class="founders-chapter__body">
            <h4 class="founders-chapter__title">A Village Upbringing</h4>
            <p>Sakshi and Meenakshi were born and raised in <strong>Chhajpur</strong> — a small village in Upper Shimla where life moves with the mountains. Mornings begin with crisp, unpolluted air, apple orchards in every direction, and food made the way nature intended — without additives, without compromise.</p>
            <p>Growing up surrounded by pure milk, home-churned bilona ghee, and the honest flavours of the Himalayas, the sisters learned early what real nourishment feels like. They watched their elders make ghee by hand in local homes, using milk from cows that grazed freely on high-altitude meadows — the same traditions their village had followed for generations.</p>
          </div>
        </article>

        <article class="founders-chapter">
          <span class="founders-chapter__num">02</span>
          <div class="founders-chapter__body">
            <h4 class="founders-chapter__title">Seeing the Gap</h4>
            <p>When they moved beyond Chhajpur, they saw how difficult it had become for families to find truly pure ghee. Shelves were filled with products laced with preservatives, palm oil, and artificial flavours — far from the golden ghee they had known all their lives. That is when they decided to act.</p>
          </div>
        </article>

        <article class="founders-chapter">
          <span class="founders-chapter__num">03</span>
          <div class="founders-chapter__body">
            <h4 class="founders-chapter__title">The HillNest Promise</h4>
            <p class="founders-promise"><strong>HillNest was born from a simple promise:</strong> to bring the same pure bilona ghee from their village in Chhajpur to kitchens across India — so every family can choose authenticity over adulteration, and taste the Himalayas the way they were meant to be tasted.</p>
          </div>
        </article>
      </div>
    </div>

    <ul class="founders-trust" aria-label="What HillNest stands for">
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

    <div class="founders-location">
      <span class="founders-location__icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
      </span>
      <p>From the apple orchards and pure mountain air of <strong>Chhajpur, Upper Shimla</strong> — to families across India.</p>
    </div>
  </div>
</section>

<!-- Reviews -->
<section class="reviews-section" id="reviews">
  <div class="reviews-section__glow" aria-hidden="true"></div>

  <div class="reviews-inner">
    <header class="reviews-header section-header">
      <span class="section-eyebrow">Customer Reviews</span>
      <h2 class="section-title">Trusted by <em>Families</em> Across India</h2>
      <p class="section-subtitle">Real stories from kitchens that chose purity over compromise.</p>
      <div class="divider-ornament"><span>❧</span></div>
    </header>

    <div class="reviews-summary" aria-label="Overall customer rating">
      <div class="reviews-summary__score">
        <span class="reviews-summary__number">4.9</span>
        <div class="reviews-summary__meta">
          <span class="reviews-summary__stars" aria-hidden="true">★★★★★</span>
          <span class="reviews-summary__label">Average rating</span>
        </div>
      </div>
      <div class="reviews-summary__divider" aria-hidden="true"></div>
      <ul class="reviews-summary__stats">
        <li>
          <strong>2,000+</strong>
          <span>Happy families</span>
        </li>
        <li>
          <strong>100%</strong>
          <span>Pure A2 bilona</span>
        </li>
        <li>
          <strong>Pan-India</strong>
          <span>Home delivery</span>
        </li>
      </ul>
    </div>

    <div class="reviews-grid">
      <article class="review-card">
        <div class="review-card__top">
          <svg class="review-card__quote" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179zm10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179z"/></svg>
          <span class="review-card__rating" aria-label="5 out of 5 stars">★★★★★</span>
        </div>
        <blockquote class="review-card__text">The aroma alone took me back to my grandmother's kitchen. This is what real ghee should taste like. We've been ordering every month since we discovered HillNest.</blockquote>
        <footer class="review-card__author">
          <div class="review-card__avatar" aria-hidden="true">P</div>
          <div class="review-card__identity">
            <cite class="review-card__name">Priya Sharma</cite>
            <span class="review-card__place">Delhi</span>
          </div>
          <span class="review-card__badge">Verified Buyer</span>
        </footer>
      </article>

      <article class="review-card review-card--featured">
        <div class="review-card__top">
          <svg class="review-card__quote" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179zm10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179z"/></svg>
          <span class="review-card__rating" aria-label="5 out of 5 stars">★★★★★</span>
        </div>
        <blockquote class="review-card__text">As a nutritionist, I recommend only the best to my clients. HillNest's A2 ghee is the only one I trust — pure, rich, and genuinely made with care.</blockquote>
        <footer class="review-card__author">
          <div class="review-card__avatar" aria-hidden="true">R</div>
          <div class="review-card__identity">
            <cite class="review-card__name">Dr. Rajan Mehta</cite>
            <span class="review-card__place">Mumbai</span>
          </div>
          <span class="review-card__badge">Verified Buyer</span>
        </footer>
      </article>

      <article class="review-card">
        <div class="review-card__top">
          <svg class="review-card__quote" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179zm10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179z"/></svg>
          <span class="review-card__rating" aria-label="5 out of 5 stars">★★★★★</span>
        </div>
        <blockquote class="review-card__text">You can literally see the difference — that beautiful golden colour, the granular texture in winters. Absolutely nothing compares to HillNest for our daily cooking.</blockquote>
        <footer class="review-card__author">
          <div class="review-card__avatar" aria-hidden="true">A</div>
          <div class="review-card__identity">
            <cite class="review-card__name">Ananya Nair</cite>
            <span class="review-card__place">Bangalore</span>
          </div>
          <span class="review-card__badge">Verified Buyer</span>
        </footer>
      </article>
    </div>
  </div>
</section>

<!-- Pre-footer CTA -->
<section class="prefooter-cta" aria-label="Shop HillNest ghee">
  <div class="prefooter-cta__pattern" aria-hidden="true"></div>
  <div class="prefooter-cta__inner">
    <div class="prefooter-cta__content">
      <span class="prefooter-cta__eyebrow">From Upper Shimla to Your Kitchen</span>
      <h2 class="prefooter-cta__title">Taste the <em>Himalayas</em> Today</h2>
      <p class="prefooter-cta__desc">Pure A2 bilona ghee, hand-crafted in village homes and delivered fresh to your doorstep.</p>
      <ul class="prefooter-cta__trust">
        <li>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
          Free delivery on first order
        </li>
        <li>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
          100% pure, no preservatives
        </li>
        <li>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
          Secure checkout
        </li>
      </ul>
    </div>
    <div class="prefooter-cta__action">
      <a href="{{ route('shop.index') }}" class="prefooter-cta__btn">Shop the Collection</a>
      <a href="{{ route('about') }}" class="prefooter-cta__link">Learn our story →</a>
    </div>
  </div>
</section>

<div class="cart-overlay" id="cart-overlay" onclick="toggleCart(event)"></div>
<div class="cart-sidebar" id="cart-sidebar">
  <div class="cart-header">
    <span class="cart-title">Your Cart</span>
    <button class="cart-close" onclick="toggleCart(event)">✕</button>
  </div>
  <div class="cart-body" id="cart-body">
    <div class="cart-empty">
      <div class="cart-empty-icon">🫙</div>
      <p>Your cart is beautifully empty.<br>Let's fill it with golden goodness!</p>
    </div>
  </div>
  <div class="cart-footer" id="cart-footer" style="display:none;">
    <div class="cart-total">
      <span class="cart-total-label">Total</span>
      <span class="cart-total-val" id="cart-total">₹0</span>
    </div>
    <button class="checkout-btn">Proceed to Checkout</button>
  </div>
</div>
@endsection



