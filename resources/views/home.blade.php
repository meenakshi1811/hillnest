<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HillNest — Pure A2 Bilona Ghee</title>
  <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
</head>
<body class="home-page">

@include('partials.site-header')

<!-- HERO -->
<section class="hero">
  <div class="hero-bg-pattern"></div>
  <div class="hero-grain"></div>
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
      {{-- <div class="hero-trust">
        <div class="trust-item">
          <span class="trust-num">100%</span>
          <span class="trust-label">Pure A2 Cow</span>
        </div>
        <div class="trust-item">
          <span class="trust-num">2000+</span>
          <span class="trust-label">Happy Families</span>
        </div>
        <div class="trust-item">
          <span class="trust-num">8000ft</span>
          <span class="trust-label">Altitude Source</span>
        </div>
      </div> --}}
    </div>

    <div class="hero-visual">
      <div class="hero-circle-bg"></div>
      <div class="hero-jar-container">
        <div class="hero-jar">
            <img src="{{ asset('images/homepage_image-new.png') }}" alt="HillNest Himalayan Ghee" />
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
<section id="collection">
  <div class="section">
    <div class="section-header">
      <span class="section-eyebrow">Our Collection</span>
      <h2 class="section-title">The <em>Finest</em> Himalayan Ghee</h2>
      <p class="section-subtitle">Each variety lovingly crafted from the milk of free-grazing cows, slow-cooked to golden perfection.</p>
      <div class="divider-ornament"><span>❧</span></div>
    </div>

    <div class="products-grid">

      <div class="product-card featured">
        <div class="product-img-wrap">
          <span class="product-tag new">Bestseller</span>
          <span class="product-emoji">🫙</span>
        </div>
        <div class="product-info">
          <div class="product-name">A2 Desi Cow Ghee</div>
          <div class="product-desc">Our flagship — slow-churned from the curd of grass-fed Gir cows. Nutty aroma, golden hue, incomparable richness.</div>
          <div class="product-footer">
            <div class="product-price">
              <span class="currency">₹</span>699 <span class="unit">/ 250g</span>
            </div>
            <button class="add-cart-btn" onclick="addToCart('A2 Desi Cow Ghee', 699)">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
              Add to Cart
            </button>
          </div>
        </div>
      </div>

      <div class="product-card">
        <div class="product-img-wrap">
          <span class="product-tag">Pure</span>
          <span class="product-emoji">✨</span>
        </div>
        <div class="product-info">
          <div class="product-name">Bilona Ghee — 500g</div>
          <div class="product-desc">Double the goodness, made using the ancient wooden churner method for deep, complex flavour.</div>
          <div class="product-footer">
            <div class="product-price">
              <span class="currency">₹</span>1199 <span class="unit">/ 500g</span>
            </div>
            <button class="add-cart-btn" onclick="addToCart('Bilona Ghee 500g', 1199)">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
              Add to Cart
            </button>
          </div>
        </div>
      </div>

      <div class="product-card">
        <div class="product-img-wrap">
          <span class="product-tag new">New</span>
          <span class="product-emoji">🌿</span>
        </div>
        <div class="product-info">
          <div class="product-name">Herb-Infused Ghee</div>
          <div class="product-desc">Infused with Himalayan herbs — turmeric, ashwagandha & moringa. Wellness in every spoonful.</div>
          <div class="product-footer">
            <div class="product-price">
              <span class="currency">₹</span>849 <span class="unit">/ 250g</span>
            </div>
            <button class="add-cart-btn" onclick="addToCart('Herb-Infused Ghee', 849)">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
              Add to Cart
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Why HillNest -->
<section class="why-section">
  <div class="why-inner">
    <div class="why-content">
      <span class="section-eyebrow">Why HillNest</span>
      <h2 class="section-title">Purity You Can<br><em>Taste & Trust</em></h2>
      <p class="why-text">
        At 8,000 feet above sea level, our cows roam free on pristine alpine meadows. No hormones, no shortcuts — just nature's best, brought to your table with generations of knowledge.
      </p>
      <div class="why-grid">
        <div class="why-card">
          <div class="why-icon">🐄</div>
          <div class="why-card-title">Free-Grazing A2 Cows</div>
          <div class="why-card-text">Our cows graze on natural high-altitude pastures, producing milk rich in nutrients and flavour.</div>
        </div>
        <div class="why-card">
          <div class="why-icon">🏔️</div>
          <div class="why-card-title">Himalayan Origin</div>
          <div class="why-card-text">Sourced directly from our farm in Upper Shimla — pure air, pure water, pure ghee.</div>
        </div>
        <div class="why-card">
          <div class="why-icon">⚗️</div>
          <div class="why-card-title">Bilona Crafted</div>
          <div class="why-card-text">Ancient Vedic method: curd churned by hand in a wooden churner before slow clarification.</div>
        </div>
        <div class="why-card">
          <div class="why-icon">🌱</div>
          <div class="why-card-title">Zero Chemicals</div>
          <div class="why-card-text">No additives, artificial flavours, or preservatives. Ever. What you see is what you get.</div>
        </div>
      </div>
    </div>

    <div class="process-visual">
      <div class="process-step">
        <span class="step-num">01</span>
        <div class="step-content">
          <div class="step-title">Fresh A2 Milk</div>
          <div class="step-desc">Collected each morning from free-grazing cows in Upper Shimla's pristine valleys.</div>
        </div>
      </div>
      <div class="step-divider"></div>
      <div class="process-step">
        <span class="step-num">02</span>
        <div class="step-content">
          <div class="step-title">Hand-Churned Curd</div>
          <div class="step-desc">Milk is cultured overnight, then churned using a traditional wooden Bilona churner.</div>
        </div>
      </div>
      <div class="step-divider"></div>
      <div class="process-step">
        <span class="step-num">03</span>
        <div class="step-content">
          <div class="step-title">Slow Clarification</div>
          <div class="step-desc">Butter is gently simmered over a wood fire until golden, aromatic ghee is born.</div>
        </div>
      </div>
      <div class="step-divider"></div>
      <div class="process-step">
        <span class="step-num">04</span>
        <div class="step-content">
          <div class="step-title">Poured with Love</div>
          <div class="step-desc">Strained, cooled, and sealed in glass jars — ready to bring the mountains to your kitchen.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section class="testimonials-bg">
  <div class="testimonials-inner">
    <div class="section-header">
      <span class="section-eyebrow">What Our Customers Say</span>
      <h2 class="section-title">Loved by <em>Families</em> Across India</h2>
      <div class="divider-ornament"><span>❧</span></div>
    </div>

    <div class="testimonials-grid">
      <div class="testimonial-card">
        <div class="testimonial-quote">"</div>
        <div class="testimonial-stars">★★★★★</div>
        <div class="testimonial-text">The aroma alone took me back to my grandmother's kitchen. This is what real ghee should taste like. We've been ordering every month since we discovered HillNest.</div>
        <div class="testimonial-author">
          <div class="author-avatar">P</div>
          <div>
            <div class="author-name">Priya Sharma</div>
            <div class="author-place">Delhi</div>
          </div>
        </div>
      </div>

      <div class="testimonial-card">
        <div class="testimonial-quote">"</div>
        <div class="testimonial-stars">★★★★★</div>
        <div class="testimonial-text">As a nutritionist, I recommend only the best to my clients. HillNest's A2 ghee is the only one I trust — pure, rich, and genuinely made with care.</div>
        <div class="testimonial-author">
          <div class="author-avatar">R</div>
          <div>
            <div class="author-name">Dr. Rajan Mehta</div>
            <div class="author-place">Mumbai</div>
          </div>
        </div>
      </div>

      <div class="testimonial-card">
        <div class="testimonial-quote">"</div>
        <div class="testimonial-stars">★★★★★</div>
        <div class="testimonial-text">You can literally see the difference — that beautiful golden colour, the granular texture in winters. Absolutely nothing compares to HillNest for our daily cooking.</div>
        <div class="testimonial-author">
          <div class="author-avatar">A</div>
          <div>
            <div class="author-name">Ananya Nair</div>
            <div class="author-place">Bangalore</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Banner -->
<div class="cta-banner">
  <div class="cta-banner-inner">
    <h2>Taste the Mountains Today</h2>
    <p>Free delivery on your first order. Pure Himalayan goodness at your doorstep.</p>
    <a href="{{ route('shop.index') }}" class="btn-cta-dark">Explore the Collection</a>
  </div>
</div>

@include('partials.site-footer')

<!-- Cart Sidebar -->
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

<script>
  // ── Cart Logic ──
  let cart = [];

  function toggleCart(e) {
    if (e) e.preventDefault();
    const sidebar = document.getElementById('cart-sidebar');
    const overlay = document.getElementById('cart-overlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('open');
    document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
  }

  function addToCart(name, price) {
    const existing = cart.find(i => i.name === name);
    if (existing) {
      existing.qty++;
    } else {
      cart.push({ name, price, qty: 1, emoji: '🫙' });
    }
    renderCart();
    // Open sidebar
    document.getElementById('cart-sidebar').classList.add('open');
    document.getElementById('cart-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function changeQty(index, delta) {
    cart[index].qty += delta;
    if (cart[index].qty <= 0) cart.splice(index, 1);
    renderCart();
  }

  function renderCart() {
    const body = document.getElementById('cart-body');
    const footer = document.getElementById('cart-footer');
    const countEl = document.getElementById('cart-count');
    const totalEl = document.getElementById('cart-total');

    const totalCount = cart.reduce((s, i) => s + i.qty, 0);
    const totalPrice = cart.reduce((s, i) => s + i.price * i.qty, 0);

    countEl.textContent = totalCount;

    if (cart.length === 0) {
      body.innerHTML = `<div class="cart-empty"><div class="cart-empty-icon">🫙</div><p>Your cart is beautifully empty.<br>Let's fill it with golden goodness!</p></div>`;
      footer.style.display = 'none';
    } else {
      footer.style.display = 'block';
      totalEl.textContent = '₹' + totalPrice.toLocaleString('en-IN');
      body.innerHTML = cart.map((item, i) => `
        <div class="cart-item">
          <div class="cart-item-img">${item.emoji}</div>
          <div class="cart-item-details">
            <div class="cart-item-name">${item.name}</div>
            <div class="cart-item-price">₹${item.price.toLocaleString('en-IN')}</div>
            <div class="cart-qty">
              <button class="qty-btn" onclick="changeQty(${i}, -1)">−</button>
              <span class="qty-num">${item.qty}</span>
              <button class="qty-btn" onclick="changeQty(${i}, 1)">+</button>
            </div>
          </div>
        </div>
      `).join('');
    }
  }

  // Sticky header shadow on scroll
  window.addEventListener('scroll', () => {
    const header = document.getElementById('header');
    if (window.scrollY > 10) {
      header.style.boxShadow = '0 2px 24px rgba(30,59,47,0.08)';
    } else {
      header.style.boxShadow = 'none';
    }
  });
</script>
</body>
</html>
