@extends('layouts.admin')

@section('title', 'Expenses')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Expenses</h1>
        <p class="admin-page-header__subtitle">Log purchases by Meenakshi or Sakshi</p>
    </div>
</div>

<div class="admin-stats">
    <div class="admin-stat-card admin-stat-card--gold">
        <p class="admin-stat-card__label">Total Expenses</p>
        <p class="admin-stat-card__value admin-stat-card__value--gold" id="expense-stat-total">₹{{ number_format($stats['total'], 0) }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--forest">
        <p class="admin-stat-card__label">Meenakshi</p>
        <p class="admin-stat-card__value" id="expense-stat-meenakshi">₹{{ number_format($stats['meenakshi'], 0) }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--amber">
        <p class="admin-stat-card__label">Sakshi</p>
        <p class="admin-stat-card__value" id="expense-stat-sakshi">₹{{ number_format($stats['sakshi'], 0) }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--brown">
        <p class="admin-stat-card__label">Entries</p>
        <p class="admin-stat-card__value">{{ $stats['count'] }}</p>
    </div>
</div>

<div class="admin-expense-layout">
    <form
        method="POST"
        action="{{ route('admin.expenses.store') }}"
        class="admin-card admin-expense-form-card"
        data-expense-form
        novalidate
    >
        <div class="admin-card__head admin-expense-form-card__head">
            <div>
                <h2 class="admin-card__title">Log New Expense</h2>
                <p class="admin-expense-form-card__subtitle">Jars, domains, packaging & more</p>
            </div>
        </div>
        @include('admin.expenses._form')
    </form>

    <div class="admin-card admin-expense-table-card">
        <div class="admin-card__head">
            <h2 class="admin-card__title">Expense History</h2>
        </div>
        <div class="admin-table-wrap admin-table-wrap--scroll admin-expense-table-wrap">
            <table id="expenses-table" class="admin-table admin-dt" style="width:100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>By</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-expenses.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.expensesTable = AdminDT.init('#expenses-table', {
        ajax: '{{ route('admin.expenses.index') }}',
        order: [[0, 'desc']],
        columns: [
            { data: 'purchased_at', name: 'purchased_at', orderable: true, searchable: false },
            { data: 'title', name: 'title', orderable: true, searchable: true },
            { data: 'quantity', name: 'quantity', orderable: true, searchable: false },
            { data: 'unit_price', name: 'unit_price', orderable: true, searchable: false },
            { data: 'total_amount', name: 'total_amount', orderable: true, searchable: false },
            { data: 'purchased_by_badge', name: 'purchased_by', orderable: true, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'dt-right' }
        ]
    });
});
</script>
@endpush
