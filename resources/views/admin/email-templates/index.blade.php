@extends('layouts.admin')

@section('title', 'Email Templates')

@section('content')
<a href="{{ route('admin.emails.index') }}" class="admin-back-link">← Emails</a>
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Email Templates</h1>
        <p class="admin-page-header__subtitle">Reusable offer and announcement emails for your customers</p>
    </div>
    <div class="admin-toolbar">
        <a href="{{ route('admin.email-templates.create') }}" class="admin-btn">New template</a>
    </div>
</div>

@if($templates->isEmpty())
    <div class="admin-card">
        <div class="admin-card__body">
            <p class="admin-empty">No templates yet. Create one to start sending emails.</p>
        </div>
    </div>
@else
<div class="admin-table-wrap admin-table-wrap--scroll">
    <table class="admin-table admin-email-table">
        <thead>
            <tr>
                <th>Template</th>
                <th>Coupon</th>
                <th>Times sent</th>
                <th>Last updated</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($templates as $template)
            <tr>
                <td>
                    <a href="{{ route('admin.email-templates.edit', $template) }}" class="admin-table__link">{{ $template->name }}</a>
                    <br><span class="admin-table__muted">{{ $template->subject }}</span>
                </td>
                <td>
                    @if($template->coupon)
                        <code class="admin-code">{{ $template->coupon->code }}</code>
                        @unless($template->coupon->isAvailable())
                            <br><span class="admin-badge admin-badge--expired">Expired</span>
                        @endunless
                    @else
                        <span class="admin-table__muted">—</span>
                    @endif
                </td>
                <td>{{ $template->campaigns_count }}</td>
                <td>{{ $template->updated_at->format('d M Y') }}</td>
                <td>
                    <div class="admin-row-actions">
                        <a href="{{ route('admin.emails.create', ['template' => $template->id]) }}" class="admin-btn admin-btn--sm">Send</a>
                        <button type="button" class="admin-icon-btn" data-email-preview data-template-id="{{ $template->id }}" title="Preview" aria-label="Preview {{ $template->name }}">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                        <a href="{{ route('admin.email-templates.edit', $template) }}" class="admin-icon-btn" title="Edit" aria-label="Edit {{ $template->name }}">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                        </a>
                        <button type="button" class="admin-icon-btn admin-icon-btn--danger" data-email-template-delete data-url="{{ route('admin.email-templates.destroy', $template) }}" data-label="{{ $template->name }}" title="Delete" aria-label="Delete {{ $template->name }}">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection

@push('scripts')
<script>window.emailPreviewUrl = @json(route('admin.email-templates.preview'));</script>
<script src="{{ asset('js/admin-emails.js') }}?v={{ filemtime(public_path('js/admin-emails.js')) }}"></script>
@endpush
