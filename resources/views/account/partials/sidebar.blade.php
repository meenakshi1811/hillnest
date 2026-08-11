@php
    $active = $active ?? 'orders';
@endphp

<aside class="account-sidebar" aria-label="Account menu">
    <div class="account-user-card">
        <div class="account-user-card__avatar" aria-hidden="true">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div>
            <p class="account-user-card__welcome">Welcome back</p>
            <h2>{{ $user->name }}</h2>
            <p class="account-user-card__email">{{ $user->loginIdentifier() }}</p>
        </div>
    </div>

    <nav class="account-menu">
        <a href="{{ route('account.orders') }}" class="account-menu__link {{ $active === 'orders' ? 'is-active' : '' }}">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/></svg>
            My Orders
        </a>
        <a href="{{ route('account.profile') }}" class="account-menu__link {{ $active === 'profile' ? 'is-active' : '' }}">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Profile
        </a>
        <a href="{{ route('shop.index') }}" class="account-menu__link">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            Shop Ghee
        </a>
    </nav>

    <div class="account-stats">
        <div class="account-stat">
            <span class="account-stat__value">{{ $stats['total_orders'] }}</span>
            <span class="account-stat__label">Orders</span>
        </div>
        <div class="account-stat">
            <span class="account-stat__value">{{ $stats['delivered'] }}</span>
            <span class="account-stat__label">Delivered</span>
        </div>
        <div class="account-stat account-stat--wide">
            <span class="account-stat__value">₹{{ number_format($stats['total_spent'], 0) }}</span>
            <span class="account-stat__label">Total spent</span>
        </div>
    </div>
</aside>
