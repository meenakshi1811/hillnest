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
<footer class="site-footer">
  <div class="site-footer__accent" aria-hidden="true"></div>
  <div class="footer-inner">
    <div class="footer-grid">
      <div class="footer-brand">
        <a href="{{ route('home') }}" class="footer-brand__logo">
          <img src="{{ asset('images/logo.png') }}" alt="HillNest" />
        </a>
        <p class="footer-brand__tagline">Pure A2 bilona ghee from Upper Shimla — nurtured by nature, made with love in village homes.</p>
        <div class="footer-contact">
          <a href="mailto:hillnestofficial@gmail.com" class="footer-contact__item">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 6l-10 7L2 6"/></svg>
            <span>hillnestofficial@gmail.com</span>
          </a>
        </div>
        <div class="footer-socials">
          <a href="https://www.instagram.com/hillnest_official?igsi=MW5xZjYydjh4NGc1ZA==" class="footer-social" target="_blank" rel="noopener noreferrer" aria-label="Follow HillNest on Instagram">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
          </a>
          <a href="https://www.facebook.com/share/1Jy8Z3zAU2/?mibextid=wwXIfr" class="footer-social" target="_blank" rel="noopener noreferrer" aria-label="Follow HillNest on Facebook">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
          </a>
        </div>
      </div>

      <div class="footer-col">
        <div class="footer-col-title">Shop</div>
        <ul class="footer-links">
          <li><a href="{{ route('shop.index') }}">All Products</a></li>
          <li><a href="{{ route('shop.index') }}">A2 Desi Cow Ghee</a></li>
          <li><a href="{{ route('shop.index') }}">Bilona Ghee</a></li>
          <li><a href="{{ route('shop.index') }}">Gift Hampers</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <div class="footer-col-title">Company</div>
        <ul class="footer-links">
          <li><a href="{{ route('about') }}">Our Story</a></li>
          <li><a href="{{ route('home') }}#our-story">Bilona Process</a></li>
          <li><a href="{{ route('home') }}#how-we-started">How We Started</a></li>
          <li><a href="{{ route('home') }}#delivery">Pan India Delivery</a></li>
        </ul>
      </div>

      <div class="footer-col footer-home">
        <div class="footer-col-title">Our Home</div>
        <p class="footer-home__lead">Handcrafted in the hills of Upper Shimla — every jar made with village care.</p>
        <address class="footer-home__address">
          Chhajpur, Village Dharmana, P.O. Anti<br>
          Tehsil Jubbal, Distt. Shimla<br>
          Himachal Pradesh, India
        </address>
        <ul class="footer-home__highlights">
          <li>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
            Free delivery above ₹999
          </li>
          <li>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
            Pure A2 bilona ghee
          </li>
          <li>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
            Small-batch village craft
          </li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <span class="footer-bottom__copy">© {{ date('Y') }} HillNest. All rights reserved.</span>
      <div class="footer-bottom-links">
        <a href="{{ route('privacy') }}">Privacy Policy</a>
        <a href="{{ route('terms') }}">Terms of Use</a>
        <a href="{{ route('refund') }}">Refund Policy</a>
      </div>
    </div>
  </div>
</footer>
