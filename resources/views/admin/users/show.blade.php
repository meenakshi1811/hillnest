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
        <form method="POST" action="{{ route('admin.users.impersonate', $user) }}" onsubmit="return confirm('Log in as {{ $user->name }}?');">
            @csrf
            <button type="submit" class="admin-btn admin-btn--sm admin-btn--ghost">Login as customer</button>
        </form>
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

<div class="admin-stats">
    <div class="admin-stat-card admin-stat-card--gold">
        <p class="admin-stat-card__label">Last Visit</p>
        <p class="admin-stat-card__value admin-stat-card__value--gold" style="font-size:22px">{{ $activity['last_visit']?->diffForHumans() ?? 'Not yet' }}</p>
        @if($activity['last_visit'])
            <p class="admin-stat-card__hint">{{ $activity['last_visit']->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}</p>
        @endif
    </div>
    <div class="admin-stat-card admin-stat-card--forest">
        <p class="admin-stat-card__label">Visits</p>
        <p class="admin-stat-card__value">{{ $activity['visits'] }}</p>
        <p class="admin-stat-card__hint">{{ $activity['pages'] }} pages viewed</p>
    </div>
    <div class="admin-stat-card admin-stat-card--brown">
        <p class="admin-stat-card__label">Total Time on Site</p>
        <p class="admin-stat-card__value">{{ $activity['time'] }}</p>
        <p class="admin-stat-card__hint">while logged in</p>
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
<div class="admin-card admin-section-gap">
    <div class="admin-card__head">
        <h2 class="admin-card__title">Cart Activity</h2>
    </div>
    <div class="admin-table-wrap admin-table-wrap--scroll" style="border:0;box-shadow:none;border-radius:0">
        <table id="user-cart-table" class="admin-table admin-dt" style="width:100%">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Product</th>
                    <th>Action</th>
                    <th>Qty</th>
                    <th>Value</th>
                    <th>Result</th>
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

    AdminDT.init('#user-cart-table', {
        ajax: { url: '{{ route('admin.users.show', $user) }}', data: function (d) { d.table = 'cart'; } },
        order: [[0, 'desc']],
        columns: [
            { data: 'created_at', name: 'created_at', orderable: true, searchable: false },
            { data: 'product_name', name: 'product_name', orderable: true, searchable: true },
            { data: 'action_badge', name: 'action', orderable: true, searchable: false },
            { data: 'quantity', name: 'quantity', orderable: true, searchable: false },
            { data: 'value', name: 'value', orderable: true, searchable: false },
            { data: 'outcome', name: 'outcome', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
