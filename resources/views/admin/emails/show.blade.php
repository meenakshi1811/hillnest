@extends('layouts.admin')

@section('title', $campaign->template_name)

@section('content')
<a href="{{ route('admin.emails.index') }}" class="admin-back-link">← Emails</a>
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">{{ $campaign->template_name }}</h1>
        <p class="admin-page-header__subtitle">
            {{ $campaign->subject }} · {{ $campaign->audience_label }} · {{ $campaign->created_at->format('d M Y, h:i A') }}
            @if($campaign->createdBy) · by {{ $campaign->createdBy->name }} @endif
        </p>
    </div>
    @unless($campaign->isComplete())
    <div class="admin-toolbar">
        <button type="button" class="admin-btn" data-email-resume data-send-url="{{ route('admin.emails.send', $campaign) }}" data-pending="{{ $campaign->pendingCount() }}">
            Resume sending ({{ $campaign->pendingCount() }} waiting)
        </button>
    </div>
    @endunless
</div>

<div class="admin-stats">
    <div class="admin-stat-card admin-stat-card--forest">
        <p class="admin-stat-card__label">Sent</p>
        <p class="admin-stat-card__value">{{ $campaign->sent_count }} / {{ $campaign->total_recipients }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--brown">
        <p class="admin-stat-card__label">Failed</p>
        <p class="admin-stat-card__value">{{ $campaign->failed_count }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--amber">
        <p class="admin-stat-card__label">Waiting</p>
        <p class="admin-stat-card__value">{{ $campaign->pendingCount() }}</p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card__head">
        <h2 class="admin-card__title">Recipients</h2>
    </div>
    <div class="admin-table-wrap admin-table-wrap--scroll" style="border:0;box-shadow:none;border-radius:0 0 18px 18px">
        <table class="admin-table admin-email-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Sent at</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recipients as $recipient)
                <tr>
                    <td>
                        @if($recipient->user_id)
                            <a href="{{ route('admin.users.show', $recipient->user_id) }}" class="admin-table__link">{{ $recipient->name }}</a>
                        @else
                            {{ $recipient->name }}
                        @endif
                        <br><span class="admin-table__muted">{{ $recipient->email }}</span>
                    </td>
                    <td>
                        @if($recipient->status === 'sent')
                            <span class="admin-badge admin-badge--active">Sent</span>
                        @elseif($recipient->status === 'failed')
                            <span class="admin-badge admin-badge--cancelled" title="{{ $recipient->error }}">Failed</span>
                            @if($recipient->error)
                                <br><span class="admin-table__muted admin-email-error">{{ Str::limit($recipient->error, 120) }}</span>
                            @endif
                        @else
                            <span class="admin-badge admin-badge--pending">Waiting</span>
                        @endif
                    </td>
                    <td>{{ $recipient->sent_at?->format('d M Y, h:i A') ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($recipients->hasPages())
    <div class="admin-card__body admin-email-pager">
        @if($recipients->previousPageUrl())
            <a href="{{ $recipients->previousPageUrl() }}" class="admin-btn admin-btn--sm admin-btn--ghost">← Previous</a>
        @endif
        <span class="admin-table__muted">Page {{ $recipients->currentPage() }} of {{ $recipients->lastPage() }}</span>
        @if($recipients->nextPageUrl())
            <a href="{{ $recipients->nextPageUrl() }}" class="admin-btn admin-btn--sm admin-btn--ghost">Next →</a>
        @endif
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-emails.js') }}?v={{ filemtime(public_path('js/admin-emails.js')) }}"></script>
@endpush
