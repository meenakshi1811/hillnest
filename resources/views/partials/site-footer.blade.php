<!-- Footer -->
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
