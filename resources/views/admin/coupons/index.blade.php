@extends('layouts.admin')

@section('title', 'Coupons')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Coupons</h1>
        <p class="admin-page-header__subtitle">Assign one-time discount codes to customers</p>
    </div>
</div>

<div class="admin-stats">
    <div class="admin-stat-card admin-stat-card--gold">
        <p class="admin-stat-card__label">Total Coupons</p>
        <p class="admin-stat-card__value admin-stat-card__value--gold">{{ $stats['total'] }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--forest">
        <p class="admin-stat-card__label">Active</p>
        <p class="admin-stat-card__value">{{ $stats['active'] }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--brown">
        <p class="admin-stat-card__label">Used</p>
        <p class="admin-stat-card__value">{{ $stats['used'] }}</p>
    </div>
</div>

<div class="admin-expense-layout">
    <form
        method="POST"
        action="{{ route('admin.coupons.store') }}"
        class="admin-card admin-expense-form-card"
        data-coupon-form
        novalidate
    >
        <div class="admin-card__head admin-expense-form-card__head">
            <div>
                <h2 class="admin-card__title">Assign New Coupon</h2>
                <p class="admin-expense-form-card__subtitle">Each coupon works once for the assigned customer only</p>
            </div>
        </div>
        @include('admin.coupons._form')
    </form>

    <div class="admin-card admin-expense-table-card">
        <div class="admin-card__head">
            <h2 class="admin-card__title">Coupon History</h2>
        </div>
        <div class="admin-table-wrap admin-table-wrap--scroll admin-expense-table-wrap">
            <table id="coupons-table" class="admin-table admin-dt" style="width:100%">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Customer</th>
                        <th>Discount</th>
                        <th>Status</th>
                        <th>Expires</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-coupons.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.couponsTable = AdminDT.init('#coupons-table', {
        ajax: '{{ route('admin.coupons.index') }}',
        order: [[0, 'desc']],
        columns: [
            { data: 'code', name: 'code', orderable: true, searchable: true },
            { data: 'customer', name: 'user.name', orderable: true, searchable: true },
            { data: 'discount', name: 'value', orderable: true, searchable: false },
            { data: 'status_badge', name: 'used_at', orderable: true, searchable: false },
            { data: 'expires_at', name: 'expires_at', orderable: true, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'dt-right' }
        ]
    });
});
</script>
@endpush
