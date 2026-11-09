@extends('layouts.admin')

@section('title', 'Visitors')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Visitors</h1>
        <p class="admin-page-header__subtitle">Who visits the store, how long they stay, and what they add to cart (India time)</p>
    </div>
</div>

<div class="admin-stats">
    <div class="admin-stat-card admin-stat-card--gold">
        <p class="admin-stat-card__label">Visitors Today</p>
        <p class="admin-stat-card__value admin-stat-card__value--gold">{{ $stats['today_visitors'] }}</p>
        <p class="admin-stat-card__hint">{{ $stats['today_customers'] }} logged-in {{ Str::plural('customer', $stats['today_customers']) }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--forest">
        <p class="admin-stat-card__label">Weekly Visitors</p>
        <p class="admin-stat-card__value">{{ $stats['week_visitors'] }}</p>
        <p class="admin-stat-card__hint">{{ $stats['week_customers'] }} logged-in · {{ $stats['week_visits'] }} {{ Str::plural('visit', $stats['week_visits']) }}</p>
    </div>
    <div class="admin-stat-card admin-stat-card--brown">
        <p class="admin-stat-card__label">Avg Time on Site</p>
        <p class="admin-stat-card__value">{{ $stats['avg_time'] }}</p>
        <p class="admin-stat-card__hint">{{ $stats['pages_per_visit'] }} pages per visit · last 7 days</p>
    </div>
    <div class="admin-stat-card admin-stat-card--amber">
        <p class="admin-stat-card__label">Added to Cart (7 days)</p>
        <p class="admin-stat-card__value">{{ $stats['cart_adds'] }}</p>
        <p class="admin-stat-card__hint">{{ $stats['carts_ordered'] }} of {{ $stats['carts_started'] }} carts ordered</p>
    </div>
    <div class="admin-stat-card admin-stat-card--forest">
        <p class="admin-stat-card__label">From Meta Ads (7 days)</p>
        <p class="admin-stat-card__value">{{ $stats['meta_ad_visits'] }}</p>
        <p class="admin-stat-card__hint">visits from Facebook / Instagram ads</p>
    </div>
</div>

<div class="admin-grid-2">
    <div class="admin-card">
        <div class="admin-card__body">
            <h2 class="admin-card__title">Daily Visitors (Last 14 Days)</h2>
            @php($maxVisitors = max(1, $daily->max('visitors')))
            <div class="admin-progress-list admin-section-gap">
                @foreach($daily->reverse() as $day)
                <div class="admin-progress-row">
                    <div class="admin-progress-row__meta">
                        <span>{{ $day['label'] }}</span>
                        <span><strong>{{ $day['visitors'] }}</strong> {{ Str::plural('visitor', $day['visitors']) }} · {{ $day['customers'] }} logged in · avg {{ $day['avg_time'] }}</span>
                    </div>
                    <div class="admin-progress-bar">
                        <div class="admin-progress-bar__fill" style="width: {{ $day['visitors'] / $maxVisitors * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card__body">
            <h2 class="admin-card__title">Where Visitors Come From (7 Days)</h2>
            @if($sources->isEmpty())
                <p class="admin-empty">No visits recorded yet. Data starts from the moment this update goes live.</p>
            @else
                @php($maxSource = max(1, $sources->max()))
                <div class="admin-progress-list admin-section-gap">
                    @foreach($sources as $source => $count)
                    <div class="admin-progress-row">
                        <div class="admin-progress-row__meta">
                            <span>{{ $source }}</span>
                            <span>{{ $count }} {{ Str::plural('visit', $count) }}</span>
                        </div>
                        <div class="admin-progress-bar">
                            <div class="admin-progress-bar__fill" style="width: {{ $count / $maxSource * 100 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<div class="admin-card admin-section-gap">
    <div class="admin-card__head">
        <h2 class="admin-card__title">Cart Activity</h2>
        <span class="admin-table__muted">Search by name, IP address, or product</span>
    </div>
    <div class="admin-table-wrap admin-table-wrap--scroll" style="border:0;box-shadow:none;border-radius:0 0 18px 18px">
        <table id="cart-events-table" class="admin-table admin-dt" style="width:100%">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Who</th>
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

<div class="admin-card admin-section-gap">
    <div class="admin-card__head">
        <h2 class="admin-card__title">Recent Visits</h2>
        <span class="admin-table__muted">Search by name, IP address, source, or page</span>
    </div>
    <div class="admin-table-wrap admin-table-wrap--scroll" style="border:0;box-shadow:none;border-radius:0 0 18px 18px">
        <table id="visits-table" class="admin-table admin-dt" style="width:100%">
            <thead>
                <tr>
                    <th>Started</th>
                    <th>Who</th>
                    <th>Device</th>
                    <th>Source</th>
                    <th>Landing page</th>
                    <th>Pages</th>
                    <th>Time on site</th>
                    <th>Cart</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    AdminDT.init('#cart-events-table', {
        ajax: { url: '{{ route('admin.visitors.index') }}', data: function (d) { d.table = 'cart'; } },
        order: [[0, 'desc']],
        columns: [
            { data: 'created_at', name: 'created_at', orderable: true, searchable: false },
            { data: 'visitor', name: 'ip_address', orderable: false, searchable: true },
            { data: 'product_name', name: 'product_name', orderable: true, searchable: true },
            { data: 'action_badge', name: 'action', orderable: true, searchable: false },
            { data: 'quantity', name: 'quantity', orderable: true, searchable: false },
            { data: 'value', name: 'value', orderable: true, searchable: false },
            { data: 'outcome', name: 'outcome', orderable: false, searchable: false }
        ]
    });

    AdminDT.init('#visits-table', {
        ajax: { url: '{{ route('admin.visitors.index') }}', data: function (d) { d.table = 'visits'; } },
        order: [[0, 'desc']],
        columns: [
            { data: 'created_at', name: 'created_at', orderable: true, searchable: false },
            { data: 'visitor', name: 'ip_address', orderable: false, searchable: true },
            { data: 'device_cell', name: 'device', orderable: true, searchable: false },
            { data: 'source', name: 'source', orderable: true, searchable: true },
            { data: 'landing_path', name: 'landing_path', orderable: false, searchable: true },
            { data: 'page_views', name: 'page_views', orderable: true, searchable: false },
            { data: 'time_on_site', name: 'last_seen_at', orderable: false, searchable: false },
            { data: 'cart_cell', name: 'cart_adds', orderable: true, searchable: false }
        ]
    });
});
</script>
@endpush
