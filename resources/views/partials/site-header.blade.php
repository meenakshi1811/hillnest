<!-- Announcement Bar -->
@inject('shippingService', 'App\Services\ShippingService')
@inject('saleBanner', 'App\Services\SaleBannerService')
@if($saleBanner->isActive())
  @php
    $saleMessages = array_values(array_filter([
        '🪔 '.$saleBanner->name().' is Live'.($saleBanner->maxDiscountPercent() > 0 ? ' — Save up to '.$saleBanner->maxDiscountPercent().'% on Pure A2 Bilona Ghee' : ''),
        $saleBanner->coupon() ? '🎁 Extra '.$saleBanner->coupon()->value_label.' off with code '.$saleBanner->coupon()->code : null,
        $shippingService->freeDeliveryLabel() ? '🚚 '.$shippingService->freeDeliveryLabel() : null,
        '✨ Gift pure Himalayan ghee to your loved ones this festive season',
        '🌿 Pure A2 Bilona Ghee from Upper Shimla',
    ]));
  @endphp
  <div class="announcement announcement--sale">
    <a href="{{ $saleBanner->coupon() ? route('diwali.offer') : route('shop.index') }}" class="announcement-ticker" aria-label="{{ $saleMessages[0] }} — see the offer">
      @foreach([false, true] as $isCopy)
        <span class="announcement-ticker__group" @if($isCopy) aria-hidden="true" @endif>
          @foreach($saleMessages as $saleMessage)
            <span class="announcement-ticker__item">{{ $saleMessage }}</span>
          @endforeach
        </span>
      @endforeach
    </a>
  </div>
@else
  <div class="announcement">
    <span>🌿</span> @if($shippingService->freeDeliveryLabel()){{ $shippingService->freeDeliveryLabel() }} &nbsp;·&nbsp; @endif Pure A2 Bilona Ghee from Upper Shimla <span>🌿</span>
  </div>
@endif

@if($saleBanner->isActive() && ! request()->routeIs('checkout.*'))
  <script src="{{ asset('js/festive-fireworks.js') }}?v={{ filemtime(public_path('js/festive-fireworks.js')) }}" defer></script>
@endif

<!-- Header -->
<header id="header">
  <div class="header-inner">
    <button type="button" class="nav-menu-toggle" id="nav-menu-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-menu" data-mobile-menu-toggle>
      <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h11"/></svg>
    </button>

    <nav class="nav-left">
      <a href="{{ route('home') }}">Home</a>
      <a href="{{ route('shop.index') }}">Shop</a>
      <a href="{{ route('about') }}">Our Story</a>
      <a href="{{ route('blog.index') }}">Blog</a>
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

{{-- Phone menu. Lives outside #header: the header's backdrop-filter would trap a fixed panel inside it. --}}
<div class="mobile-menu" id="mobile-menu" hidden>
  <div class="mobile-menu__scrim" data-mobile-menu-close></div>
  <div class="mobile-menu__panel" role="dialog" aria-modal="true" aria-label="Menu">
    <div class="mobile-menu__head">
      <a href="{{ route('home') }}" class="mobile-menu__logo" aria-label="HillNest home">
        <img src="{{ asset('images/logo.png') }}" alt="HillNest" width="52" height="52">
      </a>
      <button type="button" class="mobile-menu__close" aria-label="Close menu" data-mobile-menu-close>
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>

    <ul class="mobile-menu__links">
      <li><a href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Home</a></li>
      <li><a href="{{ route('shop.index') }}" @if(request()->routeIs('shop.*')) aria-current="page" @endif>Shop Ghee</a></li>
      <li><a href="{{ route('about') }}" @if(request()->routeIs('about')) aria-current="page" @endif>Our Story</a></li>
      <li><a href="{{ route('blog.index') }}" @if(request()->routeIs('blog.*')) aria-current="page" @endif>Blog</a></li>
      <li><a href="{{ route('contact') }}" @if(request()->routeIs('contact')) aria-current="page" @endif>Contact Us</a></li>
    </ul>

    <ul class="mobile-menu__account">
      @auth
        <li><a href="{{ route('account.orders') }}">My Orders</a></li>
        <li><a href="{{ route('account.profile') }}">My Profile</a></li>
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
          </form>
        </li>
      @else
        <li><a href="{{ route('login') }}">Log in</a></li>
        <li><a href="{{ route('register') }}">Create an account</a></li>
      @endauth
    </ul>

    <div class="mobile-menu__foot">
      @if($shippingService->freeDeliveryLabel())
        <p>🚚 {{ $shippingService->freeDeliveryLabel() }}</p>
      @endif
      <p>Pure A2 bilona ghee from Chhajpur, Upper Shimla</p>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var menu = document.getElementById('mobile-menu');
  var toggle = document.getElementById('nav-menu-toggle');
  if (!menu || !toggle) return;
  var lastFocus = null;

  function openMenu() {
    lastFocus = document.activeElement;
    menu.hidden = false;
    requestAnimationFrame(function () { menu.classList.add('is-open'); });
    toggle.setAttribute('aria-expanded', 'true');
    document.documentElement.classList.add('mobile-menu-open');
    var first = menu.querySelector('.mobile-menu__links a');
    if (first) setTimeout(function () { first.focus(); }, 60);
  }

  function closeMenu() {
    menu.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
    document.documentElement.classList.remove('mobile-menu-open');
    setTimeout(function () { menu.hidden = true; }, 220);
    if (lastFocus && lastFocus.focus) lastFocus.focus();
  }

  toggle.addEventListener('click', function () {
    if (menu.hidden) openMenu(); else closeMenu();
  });
  menu.querySelectorAll('[data-mobile-menu-close]').forEach(function (el) {
    el.addEventListener('click', closeMenu);
  });
  menu.querySelectorAll('.mobile-menu__links a, .mobile-menu__account a').forEach(function (a) {
    a.addEventListener('click', function () { closeMenu(); });
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !menu.hidden) closeMenu();
  });
  // Rotating to a wide screen while the menu is open: put it away.
  window.addEventListener('resize', function () {
    if (!menu.hidden && window.innerWidth > 640) closeMenu();
  });
});
</script>

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
