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
<footer>
  <div class="footer-inner">
    <div class="footer-grid">
      <div class="footer-logo-col">
        <img src="{{ asset('images/logo.png') }}" alt="HillNest" />
        <p class="footer-tagline">Pure • Organic • Himalayan<br>Nurtured by Nature, Made with Love.<br>From the heart of Upper Shimla.</p>
        <div class="footer-socials">
          <a href="#" class="social-btn">𝕏</a>
          <a href="#" class="social-btn">f</a>
          <a href="#" class="social-btn">in</a>
          <a href="#" class="social-btn">▶</a>
        </div>
      </div>

      <div>
        <div class="footer-col-title">Shop</div>
        <ul class="footer-links">
          <li><a href="{{ route('shop.index') }}">A2 Desi Cow Ghee</a></li>
          <li><a href="{{ route('shop.index') }}">Bilona Ghee</a></li>
          <li><a href="{{ route('shop.index') }}">Herb-Infused Ghee</a></li>
          <li><a href="{{ route('shop.index') }}">Gift Hampers</a></li>
          <li><a href="{{ route('shop.index') }}">Bulk Orders</a></li>
        </ul>
      </div>

      <div>
        <div class="footer-col-title">Company</div>
        <ul class="footer-links">
          <li><a href="{{ route('about') }}">Our Story</a></li>
          <li><a href="{{ route('about') }}">The Farm</a></li>
          <li><a href="{{ route('about') }}">Bilona Process</a></li>
          <li><a href="{{ route('about') }}">Blog</a></li>
          <li><a href="{{ route('about') }}">Contact Us</a></li>
        </ul>
      </div>

      <div class="footer-newsletter">
        <div class="footer-col-title">Stay in the Loop</div>
        <p>Get recipes, wellness tips, and first access to new arrivals straight to your inbox.</p>
        <div class="newsletter-form">
          <input type="email" placeholder="your@email.com" />
          <button>Subscribe</button>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <span>© {{ date('Y') }} HillNest. All rights reserved.</span>
      <div class="footer-bottom-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Use</a>
        <a href="#">Refund Policy</a>
      </div>
    </div>
  </div>
</footer>
