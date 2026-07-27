@extends('layouts.admin')

@section('title', 'Reviews')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Reviews</h1>
        <p class="admin-page-header__subtitle">Customer ratings and feedback from verified orders</p>
    </div>
</div>

<div class="admin-stats">
    <div class="admin-stat-card admin-stat-card--gold">
        <p class="admin-stat-card__label">Total Reviews</p>
        <p class="admin-stat-card__value admin-stat-card__value--gold">{{ $stats['total'] }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--forest">
        <p class="admin-stat-card__label">Average Rating</p>
        <p class="admin-stat-card__value">{{ number_format($stats['average'], 1) }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--brown">
        <p class="admin-stat-card__label">5-Star Reviews</p>
        <p class="admin-stat-card__value">{{ $stats['five_star'] }}</p>
    </div>
</div>

<div class="admin-card admin-expense-table-card">
    <div class="admin-card__head">
        <h2 class="admin-card__title">All Reviews</h2>
    </div>
    <div class="admin-table-wrap admin-table-wrap--scroll admin-expense-table-wrap">
        <table id="reviews-table" class="admin-table admin-dt" style="width:100%">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Order</th>
                    <th>Submitted</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-reviews.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.reviewsTable = AdminDT.init('#reviews-table', {
        ajax: '{{ route('admin.reviews.index') }}',
        order: [[5, 'desc']],
        columns: [
            { data: 'product_cell', name: 'product.name', orderable: true, searchable: true },
            { data: 'customer', name: 'user.name', orderable: true, searchable: true },
            { data: 'rating_stars', name: 'rating', orderable: true, searchable: false },
            { data: 'comment_cell', name: 'comment', orderable: false, searchable: true },
            { data: 'order_link', name: 'order.order_number', orderable: true, searchable: true },
            { data: 'created_at', name: 'created_at', orderable: true, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'dt-right' }
        ]
    });
});
</script>
@endpush
