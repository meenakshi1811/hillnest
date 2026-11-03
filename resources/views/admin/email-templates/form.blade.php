@extends('layouts.admin')

@section('title', $template ? 'Edit Template' : 'New Template')

@section('content')
<a href="{{ route('admin.email-templates.index') }}" class="admin-back-link">← Email Templates</a>
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">{{ $template ? 'Edit Template' : 'New Template' }}</h1>
        <p class="admin-page-header__subtitle">{{ $template ? $template->name : 'Create an offer, discount, or announcement email' }}</p>
    </div>
</div>

<form
    method="POST"
    action="{{ $template ? route('admin.email-templates.update', $template) : route('admin.email-templates.store') }}"
    class="admin-detail-grid"
    data-email-template-form
    novalidate
>
    @csrf
    @if($template)
        @method('PATCH')
    @endif

    <div class="admin-card">
        <div class="admin-card__body admin-form-grid">
            <div class="admin-field">
                <label class="admin-label" for="name">Template name</label>
                <input type="text" id="name" name="name" class="admin-input" maxlength="100" required value="{{ old('name', $template->name ?? '') }}" placeholder="e.g. Diwali Offer">
                <p class="admin-field__hint">Only you see this name.</p>
                <p class="admin-field__error" data-field-error="name" hidden></p>
            </div>

            <div class="admin-field">
                <label class="admin-label" for="subject">Email subject</label>
                <input type="text" id="subject" name="subject" class="admin-input" maxlength="200" required value="{{ old('subject', $template->subject ?? '') }}" placeholder="e.g. 🪔 Diwali Special: {discount} off pure A2 Bilona Ghee">
                <p class="admin-field__error" data-field-error="subject" hidden></p>
            </div>

            <div class="admin-field">
                <label class="admin-label" for="preheader">Preview text <span class="admin-label__optional">(optional)</span></label>
                <input type="text" id="preheader" name="preheader" class="admin-input" maxlength="200" value="{{ old('preheader', $template->preheader ?? '') }}" placeholder="Shown next to the subject in the inbox">
                <p class="admin-field__error" data-field-error="preheader" hidden></p>
            </div>

            <div class="admin-field">
                <label class="admin-label" for="heading">Heading</label>
                <input type="text" id="heading" name="heading" class="admin-input" maxlength="200" required value="{{ old('heading', $template->heading ?? '') }}" placeholder="e.g. Happy Diwali, {first_name}!">
                <p class="admin-field__error" data-field-error="heading" hidden></p>
            </div>

            <div class="admin-field">
                <label class="admin-label" for="body">Message</label>
                <textarea id="body" name="body" class="admin-textarea" rows="9" maxlength="5000" required placeholder="Write your message. Leave a blank line between paragraphs.">{{ old('body', $template->body ?? '') }}</textarea>
                <p class="admin-field__hint">Leave a blank line between paragraphs. Use **double stars** for bold and start a line with "- " for a bullet list.</p>
                <p class="admin-field__error" data-field-error="body" hidden></p>
            </div>

            <div class="admin-field">
                <label class="admin-label" for="coupon_id">Coupon <span class="admin-label__optional">(optional)</span></label>
                <select id="coupon_id" name="coupon_id" class="admin-select">
                    <option value="">No coupon</option>
                    @foreach($coupons as $coupon)
                        <option value="{{ $coupon->id }}" @selected((string) old('coupon_id', $template->coupon_id ?? '') === (string) $coupon->id)>
                            {{ $coupon->code }} — {{ $coupon->value_label }} off{{ $coupon->isAvailable() ? '' : ' (expired)' }}
                        </option>
                    @endforeach
                </select>
                <p class="admin-field__hint">Shows the code in a highlighted box. Only coupons for all customers are listed; create one in Coupons first.</p>
                <p class="admin-field__error" data-field-error="coupon_id" hidden></p>
            </div>

            <div class="admin-form-grid admin-form-grid--2">
                <div class="admin-field">
                    <label class="admin-label" for="button_label">Button text <span class="admin-label__optional">(optional)</span></label>
                    <input type="text" id="button_label" name="button_label" class="admin-input" maxlength="60" value="{{ old('button_label', $template->button_label ?? '') }}" placeholder="e.g. Shop now">
                    <p class="admin-field__error" data-field-error="button_label" hidden></p>
                </div>
                <div class="admin-field">
                    <label class="admin-label" for="button_url">Button link</label>
                    <input type="text" id="button_url" name="button_url" class="admin-input" maxlength="500" value="{{ old('button_url', $template->button_url ?? '') }}" placeholder="/shop">
                    <p class="admin-field__hint">Use /shop for a page on this site, or a full https:// link.</p>
                    <p class="admin-field__error" data-field-error="button_url" hidden></p>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card admin-side-panel">
        <div class="admin-card__body admin-form-grid">
            <div>
                <h2 class="admin-card__title">Personalise</h2>
                <p class="admin-field__hint" style="margin-top:6px">Click one to add it where you were last typing (subject, preview text, heading, or message).</p>
                <ul class="admin-placeholder-list">
                    @foreach(\App\Models\EmailTemplate::PLACEHOLDERS as $placeholder => $meaning)
                        <li>
                            <button type="button" class="admin-placeholder" data-copy-placeholder="{{ $placeholder }}">{{ $placeholder }}</button>
                            <span>{{ $meaning }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <button type="button" class="admin-btn admin-btn--ghost admin-btn--block" data-email-preview>Preview email</button>

            <div class="admin-field admin-email-test">
                <label class="admin-label" for="test_email">Send a test email</label>
                <div class="admin-input-group">
                    <input type="email" id="test_email" name="test_email" class="admin-input" placeholder="you@example.com" value="{{ auth()->user()->email }}">
                    <button type="button" class="admin-btn admin-btn--sm" data-email-test>
                        <span class="admin-btn__loader" aria-hidden="true"></span>
                        <span class="admin-btn__text">Send test</span>
                    </button>
                </div>
                <p class="admin-field__hint">Sends this email, as it is now, with a sample name.</p>
                <p class="admin-field__error" data-field-error="test_email" hidden></p>
            </div>

            <button type="submit" class="admin-btn admin-btn--block" data-email-template-submit>
                <span class="admin-btn__loader" aria-hidden="true"></span>
                <span class="admin-btn__text">{{ $template ? 'Save changes' : 'Save template' }}</span>
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    window.emailPreviewUrl = @json(route('admin.email-templates.preview'));
    window.emailTestUrl = @json(route('admin.email-templates.test'));
</script>
<script src="{{ asset('js/admin-emails.js') }}?v={{ filemtime(public_path('js/admin-emails.js')) }}"></script>
@endpush
