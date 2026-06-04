<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Hillnest — Pure bilona cow ghee from upper Shimla. Himalayan purity, traditional churn, delivered across India.">
    <title>@yield('title', 'Hillnest | Pure Bilona Cow Ghee | Upper Shimla')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-cream">
    <header class="site-header sticky-top">
        <nav class="navbar navbar-expand-lg py-0">
            <div class="container-xxl d-flex align-items-center justify-content-between gap-3 px-3 px-sm-4">
                <a href="{{ route('home') }}" class="logo-link d-inline-flex align-items-center text-decoration-none" aria-label="Hillnest home">
                    <img src="{{ asset('images/logo.png') }}" alt="Hillnest" class="logo-img">
                </a>

                <div class="d-none d-lg-flex align-items-center justify-content-center gap-2 mx-auto header-nav">
                    <a href="{{ route('home') }}" class="nav-link header-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('shop.index') }}" class="nav-link header-nav-link {{ request()->routeIs('shop.*') ? 'active' : '' }}">Shop</a>
                    <a href="{{ route('about') }}" class="nav-link header-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Our Story</a>
                    <a href="{{ route('shop.index') }}#collection" class="nav-link header-nav-link">Bilona Ghee</a>
                </div>

                <div class="d-flex align-items-center gap-2 header-actions">
                    <a href="{{ route('shop.index') }}" class="btn btn-light header-action d-none d-lg-inline-flex align-items-center gap-2" aria-label="Search products">
                        <svg class="header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Search</span>
                    </a>

                    @auth
                        <a href="{{ route('account.orders') }}" class="btn btn-light header-action d-none d-lg-inline-flex">My Account</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-light header-action d-none d-lg-inline-flex">Log in</a>
                    @endauth

                    <a href="{{ route('cart.index') }}" class="btn cart-button position-relative d-inline-flex align-items-center gap-2" aria-label="Cart">
                        <svg class="header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.25 10.5V6a2.25 2.25 0 114.5 0v4.5"/></svg>
                        <span class="d-none d-sm-inline">Cart</span>
                        @if(($cartCount ?? 0) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill cart-badge">{{ $cartCount }}</span>
                        @endif
                    </a>

                    <button type="button" id="mobile-menu-btn" class="navbar-toggler d-lg-none" aria-label="Menu" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>
        </nav>

        <div id="mobile-menu" class="d-none d-lg-none mobile-menu">
            <nav class="container-xxl d-flex flex-column gap-3 px-4 py-4">
                <a href="{{ route('home') }}" class="mobile-nav-link">Home</a>
                <a href="{{ route('shop.index') }}" class="mobile-nav-link">Shop</a>
                <a href="{{ route('about') }}" class="mobile-nav-link">Our Story</a>
                <a href="{{ route('shop.index') }}#collection" class="mobile-nav-link">Bilona Ghee</a>
                @auth
                    <a href="{{ route('account.orders') }}" class="mobile-nav-link">My Orders</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link">Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="mobile-nav-link text-danger border-0 bg-transparent p-0">Logout</button></form>
                @else
                    <a href="{{ route('login') }}" class="mobile-nav-link">Log in</a>
                    <a href="{{ route('register') }}" class="mobile-nav-link">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    @if(session('success'))
        <div class="bg-emerald-50 border-b border-emerald-200 px-4 py-3 text-center text-base text-emerald-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-b border-red-200 px-4 py-3 text-center text-base text-red-800">{{ session('error') }}</div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="mt-auto bg-brand text-cream">
        <div class="mx-auto max-w-[1400px] px-4 py-14 sm:px-6 lg:px-8">
            <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-4">
                <div class="lg:col-span-1">
                    <img src="{{ asset('images/logo.png') }}" alt="Hillnest" class="h-14 w-auto max-w-[180px] object-contain mb-5 bg-white/10 rounded p-2">
                    <p class="text-base text-cream-dark/90 leading-relaxed">Pure bilona cow ghee from upper Shimla. Soon — apples from our Himalayan orchard.</p>
                    <p class="mt-4 text-sm text-gold-light font-medium uppercase tracking-wider">Pure | Traditional | Ethically Crafted</p>
                </div>
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-widest text-gold-light mb-5">Helpful Links</h4>
                    <ul class="space-y-3 text-base">
                        <li><a href="{{ route('about') }}" class="hover:text-gold transition">Our Story</a></li>
                        <li><a href="{{ route('shop.index') }}" class="hover:text-gold transition">All Products</a></li>
                        <li><a href="{{ route('account.orders') }}" class="hover:text-gold transition">Track Order</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-gold transition">Cart</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-widest text-gold-light mb-5">Shop</h4>
                    <ul class="space-y-3 text-base">
                        <li><a href="{{ route('shop.index') }}" class="hover:text-gold transition">Pure Bilona Ghee</a></li>
                        <li><span class="text-cream-dark/60">Shimla Apples — Coming Soon</span></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-widest text-gold-light mb-5">Contact Us</h4>
                    <p class="text-base leading-relaxed"><strong class="text-white">Hillnest</strong><br>Upper Shimla, Himachal Pradesh, India</p>
                    <p class="mt-3 text-base"><a href="mailto:hello@hillnest.in" class="hover:text-gold">hello@hillnest.in</a></p>
                </div>
            </div>
            <div class="mt-12 border-t border-white/10 pt-8 text-center text-sm text-cream-dark/70">
                &copy; {{ date('Y') }} Hillnest. All Rights Reserved.
            </div>
        </div>
    </footer>
</body>
</html>
