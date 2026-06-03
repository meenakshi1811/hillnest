@props(['class' => 'logo-img'])

<a href="{{ route('home') }}" {{ $attributes->merge(['class' => 'navbar-brand d-flex align-items-center gap-3 m-0 p-0 text-decoration-none']) }}>
    <img
        src="{{ hillnest_logo() }}"
        alt="Hillnest logo"
        class="{{ $class }}"
        width="168"
        height="168"
        loading="eager"
        onerror="this.onerror=null; this.src='{{ asset('images/logo.svg') }}';"
    >
    <span class="d-none d-md-block lh-1">
        <span class="d-block brand-title">Hillnest</span>
        <span class="d-block brand-subtitle">Pure Bilona Ghee</span>
    </span>
</a>
