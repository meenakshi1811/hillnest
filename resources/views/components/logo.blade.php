@props(['class' => 'logo-img'])

<a href="{{ route('home') }}" aria-label="Hillnest home" {{ $attributes->merge(['class' => 'navbar-brand logo-link d-flex align-items-center m-0 p-0 text-decoration-none']) }}>
    <img
        src="{{ hillnest_logo() }}"
        alt="Hillnest logo"
        class="{{ $class }}"
        width="228"
        height="228"
        loading="eager"
        onerror="this.onerror=null; this.src='{{ asset('images/logo.svg') }}';"
    >
</a>
