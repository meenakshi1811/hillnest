@props(['class' => 'logo-img'])

<a href="{{ route('home') }}" {{ $attributes->merge(['class' => 'inline-flex shrink-0 items-center']) }}>
    <img
        src="{{ hillnest_logo() }}"
        alt="Hillnest — Pure Bilona Ghee from Upper Shimla"
        class="{{ $class }}"
        width="280"
        height="72"
        loading="eager"
        onerror="this.onerror=null; this.src='{{ asset('images/logo.svg') }}';"
    >
</a>
