@extends('layouts.admin')

@section('title', 'New Email')

@section('content')
<a href="{{ route('admin.emails.index') }}" class="admin-back-link">← Emails</a>
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">New Email</h1>
        <p class="admin-page-header__subtitle">Choose who gets it and which template to send</p>
    </div>
</div>

@if($templates->isEmpty())
    <div class="admin-card">
        <div class="admin-card__body">
            <p class="admin-empty">You need a template first. <a href="{{ route('admin.email-templates.create') }}" class="admin-inline-link">Create a template →</a></p>
        </div>
    </div>
@else
<form method="POST" action="{{ route('admin.emails.store') }}" class="admin-detail-grid" data-email-campaign-form novalidate>
    @csrf

    <div class="admin-card">
        <div class="admin-card__head">
            <h2 class="admin-card__title">1. Recipients</h2>
        </div>
        <div class="admin-card__body admin-form-grid">
            <div class="admin-segmented" role="radiogroup" aria-label="Recipients">
                <label class="admin-segmented__option">
                    <input type="radio" name="audience" value="all" checked data-email-audience>
                    <span>All customers ({{ $reachableCount }})</span>
                </label>
                <label class="admin-segmented__option">
                    <input type="radio" name="audience" value="selected" data-email-audience>
                    <span>Selected customers</span>
                </label>
            </div>
            <p class="admin-field__hint" data-email-all-hint>
                Goes to every customer with an email address who hasn't unsubscribed or been blocked.
            </p>

            <div class="admin-email-picker" data-email-picker hidden>
                <div class="admin-email-picker__tools">
                    <input type="search" class="admin-input" placeholder="Search name or email" data-email-picker-search aria-label="Search customers">
                    <button type="button" class="admin-btn admin-btn--sm admin-btn--ghost" data-email-picker-all>Select shown</button>
                    <button type="button" class="admin-btn admin-btn--sm admin-btn--ghost" data-email-picker-none>Clear</button>
                </div>
                <p class="admin-email-picker__count"><strong data-email-picker-count>0</strong> selected</p>
                <ul class="admin-email-picker__list">
                    @foreach($customers as $customer)
                        @php($reason = $customer->is_blocked ? 'Blocked' : ($customer->marketing_unsubscribed_at ? 'Unsubscribed' : null))
                        <li data-email-picker-item data-search="{{ Str::lower($customer->name.' '.$customer->email) }}">
                            <label class="admin-email-picker__row {{ $reason ? 'is-disabled' : '' }}">
                                <input type="checkbox" name="user_ids[]" value="{{ $customer->id }}" @disabled($reason)>
                                <span class="admin-email-picker__name">{{ $customer->name }}</span>
                                <span class="admin-email-picker__email">{{ $customer->email }}</span>
                                @if($reason)
                                    <span class="admin-badge admin-badge--default">{{ $reason }}</span>
                                @endif
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
            <p class="admin-field__error" data-field-error="user_ids" hidden></p>
        </div>
    </div>

    <div class="admin-card admin-side-panel">
        <div class="admin-card__body admin-form-grid">
            <div class="admin-field">
                <label class="admin-label" for="email_template_id">2. Template</label>
                <select id="email_template_id" name="email_template_id" class="admin-select" required>
                    @foreach($templates as $template)
                        <option value="{{ $template->id }}" @selected($selectedTemplateId === $template->id)>{{ $template->name }}</option>
                    @endforeach
                </select>
                <p class="admin-field__hint"><a href="{{ route('admin.email-templates.index') }}" class="admin-inline-link">Manage templates →</a></p>
                <p class="admin-field__error" data-field-error="email_template_id" hidden></p>
            </div>

            <button type="button" class="admin-btn admin-btn--ghost admin-btn--block" data-email-preview>Preview email</button>

            <div class="admin-field admin-email-test">
                <label class="admin-label" for="test_email">3. Send a test first</label>
                <div class="admin-input-group">
                    <input type="email" id="test_email" class="admin-input" placeholder="you@example.com" value="{{ auth()->user()->email }}">
                    <button type="button" class="admin-btn admin-btn--sm" data-email-test>
                        <span class="admin-btn__loader" aria-hidden="true"></span>
                        <span class="admin-btn__text">Send test</span>
                    </button>
                </div>
                <p class="admin-field__error" data-field-error="test_email" hidden></p>
            </div>

            <button type="submit" class="admin-btn admin-btn--block" data-email-campaign-submit>
                <span class="admin-btn__loader" aria-hidden="true"></span>
                <span class="admin-btn__text">4. Send email</span>
            </button>
        </div>
    </div>
</form>
@endif
@endsection

@push('scripts')
<script>
    window.emailPreviewUrl = @json(route('admin.email-templates.preview'));
    window.emailTestUrl = @json(route('admin.email-templates.test'));
</script>
<script src="{{ asset('js/admin-emails.js') }}?v={{ filemtime(public_path('js/admin-emails.js')) }}"></script>
@endpush
