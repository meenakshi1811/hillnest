@props(['class' => 'logo-img'])

<a href="{{ route('home') }}" {{ $attributes->merge(['class' => 'inline-flex shrink-0 items-center gap-3 rounded-2xl bg-white/85 px-2.5 py-1.5 shadow-[0_10px_28px_rgba(61,41,20,0.10)] transition hover:-translate-y-0.5 hover:bg-white hover:shadow-[0_14px_34px_rgba(61,41,20,0.14)]']) }}>
    <img
        src="{{ hillnest_logo() }}"
        alt="Hillnest logo"
        class="{{ $class }}"
        width="128"
        height="128"
        loading="eager"
        onerror="this.onerror=null; this.src='{{ asset('images/logo.svg') }}';"
    >
    <span class="hidden min-[430px]:block leading-none">
        <span class="block font-display text-3xl sm:text-4xl font-semibold tracking-[0.08em] text-brand">Hillnest</span>
        <span class="mt-1 block text-[10px] sm:text-[11px] font-medium uppercase tracking-[0.24em] text-forest">Pure Bilona Ghee</span>
    </span>
</a>
