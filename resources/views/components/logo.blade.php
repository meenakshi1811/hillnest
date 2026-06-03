@props(['class' => 'logo-img'])

<a href="{{ route('home') }}" {{ $attributes->merge(['class' => 'inline-flex shrink-0 items-center rounded-2xl border border-gold/25 bg-white p-2 shadow-[0_12px_30px_rgba(61,41,20,0.16)] ring-1 ring-white/80 transition hover:-translate-y-0.5 hover:shadow-[0_16px_38px_rgba(61,41,20,0.20)]']) }}>
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
