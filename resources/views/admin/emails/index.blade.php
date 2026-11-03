@extends('layouts.admin')

@section('title', 'Emails')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Emails</h1>
        <p class="admin-page-header__subtitle">Send offers and announcements to your customers</p>
    </div>
    <div class="admin-toolbar">
        <a href="{{ route('admin.email-templates.index') }}" class="admin-btn admin-btn--ghost">Templates</a>
        <a href="{{ route('admin.emails.create') }}" class="admin-btn">New email</a>
    </div>
</div>

<div class="admin-stats">
    <div class="admin-stat-card admin-stat-card--gold">
        <p class="admin-stat-card__label">Emails Sent</p>
        <p class="admin-stat-card__value admin-stat-card__value--gold">{{ number_format($stats['emails_sent']) }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--forest">
        <p class="admin-stat-card__label">Campaigns</p>
        <p class="admin-stat-card__value">{{ $stats['campaigns'] }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--brown">
        <p class="admin-stat-card__label">Reachable Customers</p>
        <p class="admin-stat-card__value">{{ $stats['reachable'] }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--amber">
        <p class="admin-stat-card__label">Unsubscribed</p>
        <p class="admin-stat-card__value">{{ $stats['unsubscribed'] }}</p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card__head">
        <h2 class="admin-card__title">Sent Campaigns</h2>
    </div>
    @if($campaigns->isEmpty())
        <div class="admin-card__body">
            <p class="admin-empty">Nothing sent yet. Click <strong>New email</strong> to send your first offer.</p>
        </div>
    @else
    <div class="admin-table-wrap admin-table-wrap--scroll" style="border:0;box-shadow:none;border-radius:0 0 18px 18px">
        <table class="admin-table admin-email-table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Audience</th>
                    <th>Sent</th>
                    <th>Failed</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campaigns as $campaign)
                <tr>
                    <td>
                        <a href="{{ route('admin.emails.show', $campaign) }}" class="admin-table__link">{{ $campaign->template_name }}</a>
                        <br><span class="admin-table__muted">{{ $campaign->subject }}</span>
                    </td>
                    <td>{{ $campaign->audience_label }}</td>
                    <td>{{ $campaign->sent_count }} / {{ $campaign->total_recipients }}</td>
                    <td>{{ $campaign->failed_count }}</td>
                    <td>
                        @if($campaign->isComplete())
                            <span class="admin-badge admin-badge--active">Done</span>
                        @else
                            <span class="admin-badge admin-badge--pending">{{ $campaign->pendingCount() }} waiting</span>
                        @endif
                    </td>
                    <td>{{ $campaign->created_at->format('d M Y, h:i A') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
