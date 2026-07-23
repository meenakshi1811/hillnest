@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Dashboard</h1>
        <p class="admin-page-header__subtitle">Overview of Hillnest store performance</p>
    </div>
</div>

<div class="admin-stats">
    @foreach([
        ['label' => 'Total Revenue', 'value' => '₹'.number_format($stats['total_revenue'], 0), 'variant' => 'gold', 'gold' => true],
        ['label' => 'Total Orders', 'value' => $stats['orders_count'], 'variant' => 'forest'],
        ['label' => 'Pending Orders', 'value' => $stats['pending_orders'], 'variant' => 'amber'],
        ['label' => 'Customers', 'value' => $stats['users_count'], 'variant' => 'brown'],
        ['label' => 'Products', 'value' => $stats['products_count'], 'variant' => 'forest'],
    ] as $card)
    <div class="admin-stat-card admin-stat-card--{{ $card['variant'] }}">
        <p class="admin-stat-card__label">{{ $card['label'] }}</p>
        <p class="admin-stat-card__value {{ !empty($card['gold']) ? 'admin-stat-card__value--gold' : '' }}">{{ $card['value'] }}</p>
    </div>
    @endforeach
</div>

<div class="admin-grid-2">
    <div class="admin-card">
        <div class="admin-card__body">
            <h2 class="admin-card__title">Revenue (Last 6 Months)</h2>
            @if($monthlyRevenue->count())
            <div class="admin-progress-list admin-section-gap">
                @php $maxRev = $monthlyRevenue->max('revenue') ?: 1; @endphp
                @foreach($monthlyRevenue as $row)
                <div class="admin-progress-row">
                    <div class="admin-progress-row__meta">
                        <span>{{ \Carbon\Carbon::createFromFormat('Y-m', $row->month)->format('M Y') }}</span>
                        <span>₹{{ number_format($row->revenue, 0) }} · {{ $row->orders }} orders</span>
                    </div>
                    <div class="admin-progress-bar">
                        <div class="admin-progress-bar__fill" style="width: {{ min(100, ($row->revenue / $maxRev) * 100) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="admin-empty">No revenue data yet.</p>
            @endif
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card__head">
            <h2 class="admin-card__title">Recent Orders</h2>
            <a href="{{ route('admin.orders.index') }}" class="admin-card__link">View all</a>
        </div>
        <div class="admin-table-wrap admin-table-wrap--scroll" style="border:0;box-shadow:none;border-radius:0 0 18px 18px">
            <table id="recent-orders-table" class="admin-table admin-dt" style="width:100%">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
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
    AdminDT.init('#recent-orders-table', {
        ajax: '{{ route('admin.dashboard') }}',
        pageLength: 8,
        lengthMenu: [[8, 15, 25], [8, 15, 25]],
        order: [[4, 'desc']],
        columns: [
            { data: 'order_link', name: 'order_number', orderable: true, searchable: true },
            { data: 'customer_name', name: 'customer_name', orderable: true, searchable: true },
            { data: 'total', name: 'total', orderable: true, searchable: false },
            { data: 'status_badge', name: 'status', orderable: true, searchable: false },
            { data: 'created_at', name: 'created_at', orderable: true, searchable: false }
        ]
    });
});
</script>
@endpush
