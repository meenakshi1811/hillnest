<section class="legal-page-hero">
    <div class="legal-shell">
        <p class="legal-eyebrow">{{ $eyebrow ?? 'Legal' }}</p>
        <h1>{{ $title }}</h1>
        @isset($subtitle)
            <p class="legal-subtitle">{{ $subtitle }}</p>
        @endisset
        @if(($updated ?? null) !== false)
            <p class="legal-updated">Last updated: {{ $updated ?? 'July 27, 2026' }}</p>
        @endif
    </div>
</section>
<section class="legal-page-body">
    <div class="legal-shell">
        <article class="legal-card">
