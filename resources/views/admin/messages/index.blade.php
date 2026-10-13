@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Messages</h1>
        <p class="admin-page-header__subtitle">Messages sent from the Contact page</p>
    </div>
</div>

<div class="admin-stats">
    <div class="admin-stat-card admin-stat-card--gold">
        <p class="admin-stat-card__label">Total Messages</p>
        <p class="admin-stat-card__value admin-stat-card__value--gold">{{ $stats['total'] }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--amber">
        <p class="admin-stat-card__label">Unread</p>
        <p class="admin-stat-card__value">{{ $stats['unread'] }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--forest">
        <p class="admin-stat-card__label">Last 7 Days</p>
        <p class="admin-stat-card__value">{{ $stats['this_week'] }}</p>
    </div>
</div>

<div class="admin-table-wrap admin-table-wrap--scroll">
    <table id="messages-table" class="admin-table admin-dt" style="width:100%">
        <thead>
            <tr>
                <th>From</th>
                <th>Message</th>
                <th>Order</th>
                <th>Status</th>
                <th>Received</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-messages.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.messagesTable = AdminDT.init('#messages-table', {
        ajax: '{{ route('admin.messages.index') }}',
        order: [[4, 'desc']],
        columns: [
            { data: 'sender', name: 'name', orderable: true, searchable: true },
            { data: 'message_excerpt', name: 'message', orderable: false, searchable: true },
            { data: 'order_number', name: 'order_number', orderable: true, searchable: true },
            { data: 'status_badge', name: 'read_at', orderable: true, searchable: false },
            { data: 'created_at', name: 'created_at', orderable: true, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'dt-right' }
        ]
    });
});
</script>
@endpush
