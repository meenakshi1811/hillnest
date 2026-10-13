@extends('layouts.app')

@section('title', 'Contact Us — HillNest')
@section('meta_description', 'Get in touch with HillNest for help with orders, deliveries, or questions about our pure A2 bilona ghee.')

@section('content')
@php($store = config('hillnest.store'))
@include('partials.legal-header', [
    'title' => 'Contact Us',
    'eyebrow' => 'We\'re here to help',
    'subtitle' => 'Questions about an order, a delivery, or our ghee? Reach out to the HillNest team.',
    'updated' => false,
])

            <div class="contact-grid">
                <a href="mailto:{{ $store['email'] }}" class="contact-card">
                    <span class="contact-card__icon" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 6l-10 7L2 6"/></svg>
                    </span>
                    <span class="contact-card__label">Email</span>
                    <strong class="contact-card__value">{{ $store['email'] }}</strong>
                </a>

                @if($store['phone'])
                <a href="tel:{{ preg_replace('/[^\d+]/', '', $store['phone']) }}" class="contact-card">
                    <span class="contact-card__icon" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </span>
                    <span class="contact-card__label">Call us</span>
                    <strong class="contact-card__value">{{ $store['phone'] }}</strong>
                </a>
                @endif

                @if($store['whatsapp'])
                <a href="https://wa.me/{{ preg_replace('/\D/', '', $store['whatsapp']) }}" class="contact-card" target="_blank" rel="noopener noreferrer">
                    <span class="contact-card__icon" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                    </span>
                    <span class="contact-card__label">WhatsApp</span>
                    <strong class="contact-card__value">Chat with us</strong>
                </a>
                @endif

                <div class="contact-card">
                    <span class="contact-card__icon" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
                    </span>
                    <span class="contact-card__label">Our home</span>
                    <address class="contact-card__value">
                        {{ $store['name'] }}<br>
                        {!! implode('<br>', array_map('e', $store['address'])) !!}
                    </address>
                </div>
            </div>

            <section class="contact-form-section" id="contact-form" aria-labelledby="contact-form-title">
                <h2 id="contact-form-title">Send us a message</h2>
                <p class="contact-form-section__intro">Fill in the form and our team will reply to you by email.</p>

                @if(session('contact_sent'))
                    <p class="auth-status" role="status">Thank you! Your message has been sent. We'll get back to you soon.</p>
                @endif

                @php($customer = auth()->user())
                <form method="POST" action="{{ route('contact.store') }}" class="auth-form auth-form--register contact-form">
                    @csrf

                    <div class="contact-form__trap" aria-hidden="true">
                        <label for="website">Website</label>
                        <input id="website" type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="auth-field">
                        <label for="contact-name">Your name</label>
                        <div class="auth-input-wrap">
                            <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>
                            <input id="contact-name" type="text" name="name" value="{{ old('name', $customer?->name) }}" required maxlength="100" autocomplete="name" placeholder="Full name" @class(['is-invalid' => $errors->has('name')])>
                        </div>
                        @error('name')<p class="auth-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="auth-field">
                        <label for="contact-email">Email address</label>
                        <div class="auth-input-wrap">
                            <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/><path d="m22 6-10 7L2 6"/></svg>
                            <input id="contact-email" type="email" name="email" value="{{ old('email', $customer?->email) }}" required maxlength="255" autocomplete="email" placeholder="you@example.com" @class(['is-invalid' => $errors->has('email')])>
                        </div>
                        @error('email')<p class="auth-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="auth-field">
                        <label for="contact-phone">Phone (optional)</label>
                        <div class="auth-input-wrap">
                            <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <input id="contact-phone" type="tel" name="phone" value="{{ old('phone', $customer?->phone) }}" maxlength="20" autocomplete="tel" placeholder="+91 98xxxxxxxx" @class(['is-invalid' => $errors->has('phone')])>
                        </div>
                        @error('phone')<p class="auth-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="auth-field">
                        <label for="contact-order">Order number (optional)</label>
                        <div class="auth-input-wrap">
                            <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/></svg>
                            <input id="contact-order" type="text" name="order_number" value="{{ old('order_number') }}" maxlength="30" placeholder="HN-XXXXXXXX" @class(['is-invalid' => $errors->has('order_number')])>
                        </div>
                        @error('order_number')<p class="auth-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="auth-field auth-field--full">
                        <label for="contact-message">Message</label>
                        <div class="auth-input-wrap">
                            <textarea id="contact-message" name="message" rows="6" required minlength="10" maxlength="3000" placeholder="How can we help?" @class(['is-invalid' => $errors->has('message')])>{{ old('message') }}</textarea>
                        </div>
                        @error('message')<p class="auth-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="auth-field--full">
                        <button type="submit" class="auth-submit">
                            <span>Send message</span>
                            <svg aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </button>
                    </div>
                </form>
            </section>

            <div class="legal-content">
                <h2>Help with an order</h2>
                <ul>
                    <li>Please include your <strong>order number</strong> (it starts with HN-) so we can help you quickly.</li>
                    <li>Track your order and see its status in <a href="{{ route('account.orders') }}">My Orders</a>.</li>
                    <li>Damaged, leaked, or missing items must be reported within 24 hours of delivery with photos or video. See our <a href="{{ route('refund') }}">Refund Policy</a>.</li>
                    <li>Delivery timelines and charges are explained in our <a href="{{ route('shipping') }}">Shipping Policy</a>.</li>
                </ul>

                <h2>Follow us</h2>
                <p>
                    <a href="{{ $store['instagram_url'] }}" target="_blank" rel="noopener noreferrer">Instagram</a>
                    &nbsp;·&nbsp;
                    <a href="{{ $store['facebook_url'] }}" target="_blank" rel="noopener noreferrer">Facebook</a>
                </p>
            </div>
        </article>

        <nav class="legal-nav" aria-label="Related policies">
            <a href="{{ route('shipping') }}">Shipping Policy</a>
            <a href="{{ route('refund') }}">Refund Policy</a>
            <a href="{{ route('terms') }}">Terms of Use</a>
        </nav>
    </div>
</section>
@endsection
