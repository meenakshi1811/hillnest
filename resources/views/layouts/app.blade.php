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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-cream">
    {{-- Announcement bar (Rosier-style) --}}
    <div class="announcement-bar">
        <span class="inline-flex flex-wrap items-center justify-center gap-2">
            <span>☀️ Himalayan Purity Sale</span>
            <span class="hidden sm:inline text-gold-light">|</span>
            <span class="font-semibold">Flat 10% OFF + Free Shipping on orders above ₹2,000</span>
        </span>
    </div>

    <header class="sticky top-0 z-50 border-b border-gold/20 bg-cream/95 shadow-[0_18px_45px_rgba(61,41,20,0.10)] backdrop-blur-xl">
        <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8">
            <div class="flex h-[82px] md:h-[92px] items-center justify-between gap-4">
                <x-logo />

                <nav class="hidden lg:flex items-center gap-2 rounded-full border border-hill-200/80 bg-white/75 p-1.5 text-[15px] font-semibold text-brand-light shadow-inner shadow-white/80">
                    <a href="{{ route('home') }}" class="rounded-full px-5 py-2.5 transition hover:bg-gold-bg hover:text-brand {{ request()->routeIs('home') ? 'bg-gold text-white shadow-sm hover:bg-gold hover:text-white' : '' }}">Home</a>
                    <a href="{{ route('shop.index') }}" class="rounded-full px-5 py-2.5 transition hover:bg-gold-bg hover:text-brand {{ request()->routeIs('shop.*') ? 'bg-gold text-white shadow-sm hover:bg-gold hover:text-white' : '' }}">Shop</a>
                    <a href="{{ route('about') }}" class="rounded-full px-5 py-2.5 transition hover:bg-gold-bg hover:text-brand {{ request()->routeIs('about') ? 'bg-gold text-white shadow-sm hover:bg-gold hover:text-white' : '' }}">Our Story</a>
                    <a href="{{ route('shop.index') }}#collection" class="rounded-full px-5 py-2.5 transition hover:bg-gold-bg hover:text-brand">Bilona Ghee</a>
                </nav>

                <div class="flex items-center gap-1.5 sm:gap-3">
                    <a href="{{ route('shop.index') }}" class="hidden md:inline-flex h-11 w-11 items-center justify-center rounded-full border border-hill-200 bg-white text-brand-light shadow-sm transition hover:border-gold hover:bg-gold-bg hover:text-brand" aria-label="Search">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </a>

                    @auth
                        <a href="{{ route('account.orders') }}" class="hidden sm:inline-flex items-center rounded-full border border-hill-200 bg-white px-4 py-2.5 text-[15px] font-semibold text-brand-light shadow-sm transition hover:border-gold hover:bg-gold-bg hover:text-brand">My Account</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center rounded-full border border-hill-200 bg-white px-4 py-2.5 text-[15px] font-semibold text-brand-light shadow-sm transition hover:border-gold hover:bg-gold-bg hover:text-brand">Log in</a>
                    @endauth

                    <a href="{{ route('cart.index') }}" class="relative flex items-center gap-1.5 rounded-full bg-brand px-3.5 py-2.5 text-white shadow-lg shadow-brand/15 transition hover:bg-gold" aria-label="Cart">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.25 10.5V6a2.25 2.25 0 114.5 0v4.5"/></svg>
                        <span class="hidden sm:inline text-[15px] font-semibold">Cart</span>
                        @if(($cartCount ?? 0) > 0)
                            <span class="absolute -top-1 -right-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-gold-light px-1 text-[11px] font-bold text-brand ring-2 ring-cream">{{ $cartCount }}</span>
                        @endif
                    </a>

                    <button type="button" id="mobile-menu-btn" class="lg:hidden inline-flex h-11 w-11 items-center justify-center rounded-full border border-hill-200 bg-white text-brand shadow-sm" aria-label="Menu">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden lg:hidden border-t border-gold/20 bg-cream/98 px-4 py-5 shadow-inner">
            <nav class="flex flex-col gap-3 text-base font-semibold text-brand">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('shop.index') }}">Shop</a>
                <a href="{{ route('about') }}">Our Story</a>
                @auth
                    <a href="{{ route('account.orders') }}">My Orders</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}">Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="text-left text-red-700">Logout</button></form>
                @else
                    <a href="{{ route('login') }}">Log in</a>
                    <a href="{{ route('register') }}">Register</a>
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
                    <img src="{{ hillnest_logo() }}" alt="Hillnest" class="h-14 w-auto max-w-[180px] object-contain mb-5 bg-white/10 rounded p-2" onerror="this.onerror=null; this.src='{{ asset('images/logo.svg') }}';">
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
