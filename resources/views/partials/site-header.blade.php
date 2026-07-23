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
      <a href="{{ route('home') }}" class="logo-link" aria-label="HillNest home">
        <img src="{{ asset('images/logo.png') }}" alt="HillNest — Pure Himalayan Ghee" />
      </a>
    </div>

    <nav class="nav-right">
      <button type="button" class="nav-icon-btn nav-search-toggle" id="nav-search-toggle" aria-label="Search products" aria-expanded="false" aria-controls="nav-search-panel">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      </button>
      @auth
        <div class="nav-account-dropdown" id="nav-account-dropdown">
          <button type="button" class="nav-icon-btn nav-account-toggle" aria-expanded="false" aria-haspopup="true" aria-controls="nav-account-menu">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span class="nav-label">Account</span>
            <svg class="nav-account-chevron" width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
          </button>
          <div class="nav-account-menu" id="nav-account-menu" role="menu">
            <a href="{{ route('account.profile') }}" role="menuitem">My Profile</a>
            <a href="{{ route('account.orders') }}" role="menuitem">Orders</a>
            <div class="nav-account-menu-divider" aria-hidden="true"></div>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="nav-account-logout" role="menuitem">Logout</button>
            </form>
          </div>
        </div>
      @else
        <a href="{{ route('login') }}" class="nav-icon-btn">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <span class="nav-label">Login</span>
        </a>
      @endauth
      <a href="{{ route('cart.index') }}" class="nav-icon-btn cart-btn">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        <span class="nav-label">Cart</span>
        <span class="cart-badge" id="cart-count">{{ $cartCount ?? 0 }}</span>
      </a>
    </nav>
  </div>

  <div class="nav-search" id="nav-search-panel" hidden>
    <form action="{{ route('shop.index') }}" method="GET" class="nav-search__form" role="search">
      <div class="nav-search__field">
        <svg class="nav-search__icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input
          id="nav-search-input"
          class="nav-search__input"
          type="search"
          name="q"
          value="{{ request('q') }}"
          placeholder="Search ghee, jars, sizes…"
          autocomplete="off"
          aria-label="Search products"
        />
        <button type="submit" class="nav-search__submit">Search</button>
      </div>
      <button type="button" class="nav-search__close" id="nav-search-close" aria-label="Close search">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
      </button>
    </form>
  </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var toggle = document.getElementById('nav-search-toggle');
  var panel = document.getElementById('nav-search-panel');
  var input = document.getElementById('nav-search-input');
  var closeBtn = document.getElementById('nav-search-close');
  if (!toggle || !panel || !input) return;

  function openSearch() {
    panel.hidden = false;
    panel.classList.add('is-open');
    toggle.setAttribute('aria-expanded', 'true');
    setTimeout(function () { input.focus(); }, 30);
  }

  function closeSearch() {
    panel.classList.remove('is-open');
    panel.hidden = true;
    toggle.setAttribute('aria-expanded', 'false');
  }

  toggle.addEventListener('click', function (e) {
    e.stopPropagation();
    if (panel.hidden) openSearch();
    else closeSearch();
  });

  if (closeBtn) {
    closeBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      closeSearch();
    });
  }

  document.addEventListener('click', function (e) {
    if (panel.hidden) return;
    if (panel.contains(e.target) || toggle.contains(e.target)) return;
    closeSearch();
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !panel.hidden) closeSearch();
  });

  @if(request()->filled('q'))
    openSearch();
  @endif
});
</script>

@auth
<script>
document.addEventListener('DOMContentLoaded', function () {
  var dropdown = document.getElementById('nav-account-dropdown');
  if (!dropdown) return;

  var toggle = dropdown.querySelector('.nav-account-toggle');
  var menu = dropdown.querySelector('.nav-account-menu');

  toggle.addEventListener('click', function (e) {
    e.stopPropagation();
    var open = dropdown.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  });

  menu.addEventListener('click', function (e) {
    e.stopPropagation();
  });

  document.addEventListener('click', function () {
    dropdown.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      dropdown.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
    }
  });
});
</script>
@endauth
