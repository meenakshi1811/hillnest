@extends('layouts.admin')

@section('title', 'Message from ' . $contactMessage->name)

@section('content')
<a href="{{ route('admin.messages.index') }}" class="admin-back-link">← Messages</a>
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">{{ $contactMessage->name }}</h1>
        <p class="admin-page-header__subtitle">Received {{ $contactMessage->created_at->format('d M Y, h:i A') }}</p>
    </div>
</div>

<div class="admin-detail-grid">
    <div class="admin-card">
        <div class="admin-card__body">
            <h2 class="admin-card__title">Message</h2>
            <div class="admin-section-gap" style="font-size:15px;line-height:1.75;color:var(--text-mid);white-space:pre-line;overflow-wrap:anywhere">{{ $contactMessage->message }}</div>
        </div>
    </div>

    <div class="admin-card admin-side-panel">
        <div class="admin-card__body">
            <h2 class="admin-card__title">Sender</h2>
            <div class="admin-meta">
                <p>Email: <a href="mailto:{{ $contactMessage->email }}" class="admin-inline-link">{{ $contactMessage->email }}</a></p>
                @if($contactMessage->phone)
                <p>Phone: <a href="tel:{{ preg_replace('/[^\d+]/', '', $contactMessage->phone) }}" class="admin-inline-link">{{ $contactMessage->phone }}</a></p>
                @endif
                @if($contactMessage->order_number)
                <p>Order: <code class="admin-code">{{ $contactMessage->order_number }}</code></p>
                @endif
                <p>Read: {{ $contactMessage->read_at?->format('d M Y, h:i A') ?? '—' }}</p>
            </div>

            <div class="admin-form-grid admin-section-gap">
                <a href="mailto:{{ $contactMessage->email }}?subject={{ rawurlencode('Re: Your message to HillNest') }}" class="admin-btn admin-btn--block">Reply by email</a>
                <button type="button" class="admin-btn admin-btn--ghost admin-btn--block js-message-delete" data-url="{{ route('admin.messages.destroy', $contactMessage) }}" data-label="{{ $contactMessage->name }}">Delete message</button>
            </div>

            @if($contactMessage->user)
            <a href="{{ route('admin.users.show', $contactMessage->user) }}" class="admin-inline-link" style="display:inline-block;margin-top:16px">View customer profile →</a>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-messages.js') }}"></script>
@endpush
