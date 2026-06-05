<!-- Announcement Bar -->
<div class="announcement">
  <span>🌿</span> Free delivery on orders above ₹999 &nbsp;·&nbsp; Pure A2 Bilona Ghee from Upper Shimla <span>🌿</span>
</div>

<!-- Header -->
<header id="header">
  <div class="header-inner">
    <nav class="nav-left">
      <a href="{{ route('home') }}">Home</a>
      <a href="{{ route('shop.index') }}">Shop</a>
      <a href="{{ route('about') }}">Our Story</a>
      <a href="{{ route('shop.index') }}#collection">Ghee</a>
    </nav>

    <div class="logo-wrap">
      <img src="{{ asset('images/logo.png') }}" alt="HillNest — Pure Himalayan Ghee" />
    </div>

    <nav class="nav-right">
      <a href="{{ route('shop.index') }}" class="nav-icon-btn" aria-label="Search products">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      </a>
      @auth
        <a href="{{ route('account.orders') }}" class="nav-icon-btn">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          Account
        </a>
      @else
        <a href="{{ route('login') }}" class="nav-icon-btn">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          Login
        </a>
      @endauth
      <a href="{{ route('cart.index') }}" class="nav-icon-btn cart-btn">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        Cart
        <span class="cart-badge" id="cart-count">{{ $cartCount ?? 0 }}</span>
      </a>
    </nav>
  </div>
</header>
