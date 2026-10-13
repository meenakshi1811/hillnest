<section class="error-page">
    <div class="error-page__inner">
        <p class="error-page__code">{{ $code }}</p>
        <h1 class="error-page__title">{{ $heading }}</h1>
        <p class="error-page__text">{{ $text }}</p>
        <div class="error-page__actions">
            <a href="{{ route('shop.index') }}" class="btn-primary"><span>Shop Ghee</span></a>
            <a href="{{ route('home') }}" class="btn-ghost">Back to Home →</a>
        </div>
        <p class="error-page__help">Need help? <a href="{{ route('contact') }}">Contact us</a></p>
    </div>
</section>
