@extends('layouts.admin')

@section('title', $user->name)

@section('content')
<a href="{{ route('admin.users.index') }}" class="admin-back-link">← Customers</a>
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">{{ $user->name }}</h1>
        <p class="admin-customer-meta">{{ $user->contactDisplay() }}</p>
    </div>
    <div class="admin-customer-actions">
        @if($user->is_blocked)
            <span class="admin-badge admin-badge--cancelled">Blocked</span>
        @else
            <span class="admin-badge admin-badge--active">Active</span>
        @endif
        <label class="admin-toggle" title="{{ $user->is_blocked ? 'Blocked — click to unblock' : 'Active — click to block' }}">
            <input type="checkbox"
                   class="admin-toggle__input js-user-block-toggle"
                   data-url="{{ route('admin.users.toggle-block', $user) }}"
                   @checked(! $user->is_blocked)>
            <span class="admin-toggle__track" aria-hidden="true"></span>
            <span class="admin-toggle__label">{{ $user->is_blocked ? 'Blocked' : 'Active' }}</span>
        </label>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card__head">
        <h2 class="admin-card__title">Order History</h2>
    </div>
    <div class="admin-table-wrap admin-table-wrap--scroll" style="border:0;box-shadow:none;border-radius:0">
        <table id="user-orders-table" class="admin-table admin-dt" style="width:100%">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-users.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    AdminDT.init('#user-orders-table', {
        ajax: '{{ route('admin.users.show', $user) }}',
        order: [[1, 'desc']],
        columns: [
            { data: 'order_link', name: 'order_number', orderable: true, searchable: true },
            { data: 'created_at', name: 'created_at', orderable: true, searchable: false },
            { data: 'total', name: 'total', orderable: true, searchable: false },
            { data: 'status_badge', name: 'status', orderable: true, searchable: false }
        ]
    });
});
</script>
@endpush
