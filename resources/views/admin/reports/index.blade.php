@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Earnings Report</h1>
        <p class="admin-page-header__subtitle">Revenue and sales insights</p>
    </div>
    <form method="GET" class="admin-toolbar" id="reports-filter-form">
        <div class="admin-field">
            <label class="admin-label" for="from">From</label>
            <input type="date" id="from" name="from" value="{{ $from }}" class="admin-input">
        </div>
        <div class="admin-field">
            <label class="admin-label" for="to">To</label>
            <input type="date" id="to" name="to" value="{{ $to }}" class="admin-input">
        </div>
        <button type="submit" class="admin-btn admin-btn--sm" style="align-self:flex-end">Apply</button>
    </form>
</div>

<div class="admin-stats">
    <div class="admin-stat-card admin-stat-card--gold">
        <p class="admin-stat-card__label">Revenue</p>
        <p class="admin-stat-card__value admin-stat-card__value--gold">₹{{ number_format($summary['revenue'], 0) }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--forest">
        <p class="admin-stat-card__label">Orders</p>
        <p class="admin-stat-card__value">{{ $summary['orders'] }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--amber">
        <p class="admin-stat-card__label">Avg Order Value</p>
        <p class="admin-stat-card__value">₹{{ number_format($summary['avg_order'], 0) }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--brown">
        <p class="admin-stat-card__label">Shipping Collected</p>
        <p class="admin-stat-card__value">₹{{ number_format($summary['shipping'], 0) }}</p>
    </div>
</div>

<div class="admin-grid-2 admin-section-gap">
    <div class="admin-card">
        <div class="admin-card__body">
            <h2 class="admin-card__title">Daily Revenue</h2>
            <div class="admin-table-wrap admin-section-gap" style="box-shadow:none;border:0;background:transparent">
                <table id="daily-revenue-table" class="admin-table admin-dt" style="width:100%">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card__body">
            <h2 class="admin-card__title">Orders by Status</h2>
            <div class="admin-table-wrap admin-section-gap" style="box-shadow:none;border:0;background:transparent">
                <table id="orders-status-table" class="admin-table admin-dt" style="width:100%">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Summary</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="admin-card admin-section-gap">
    <div class="admin-card__body">
        <h2 class="admin-card__title">Top Products</h2>
        <div class="admin-table-wrap admin-section-gap" style="box-shadow:none;border:0;background:transparent">
            <table id="top-products-table" class="admin-table admin-dt" style="width:100%">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Units</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var reportUrl = '{{ route('admin.reports.index') }}';
    var from = '{{ $from }}';
    var to = '{{ $to }}';

    function reportAjax(table) {
        return {
            url: reportUrl,
            dataSrc: 'data',
            data: function (d) {
                d.table = table;
                d.from = from;
                d.to = to;
            }
        };
    }

    AdminDT.init('#daily-revenue-table', {
        ajax: reportAjax('daily'),
        serverSide: false,
        searching: false,
        order: [[0, 'asc']],
        columns: [
            { data: 'date', orderable: true, searchable: false },
            { data: 'summary', orderable: true, searchable: false }
        ]
    });

    AdminDT.init('#orders-status-table', {
        ajax: reportAjax('status'),
        serverSide: false,
        searching: false,
        paging: false,
        info: false,
        order: [[0, 'asc']],
        columns: [
            { data: 'status', orderable: true, searchable: false },
            { data: 'summary', orderable: true, searchable: false }
        ]
    });

    AdminDT.init('#top-products-table', {
        ajax: reportAjax('products'),
        serverSide: false,
        searching: false,
        order: [[2, 'desc']],
        columns: [
            { data: 'product_name', orderable: true, searchable: false },
            { data: 'units_sold', orderable: true, searchable: false },
            { data: 'revenue', orderable: true, searchable: false }
        ]
    });
});
</script>
@endpush
