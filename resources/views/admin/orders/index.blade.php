@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Orders</h1>
        <p class="admin-page-header__subtitle">Manage and track customer orders</p>
    </div>
    <div class="admin-toolbar">
        <select id="orders-status-filter" class="admin-select" style="width:auto;min-width:160px">
            <option value="">All statuses</option>
            @foreach(\App\Models\Order::STATUSES as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="admin-table-wrap admin-table-wrap--scroll">
    <table id="orders-table" class="admin-table admin-dt" style="width:100%">
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var table = AdminDT.init('#orders-table', {
        ajax: {
            url: '{{ route('admin.orders.index') }}',
            data: function (d) {
                d.status = document.getElementById('orders-status-filter').value;
            }
        },
        order: [[4, 'desc']],
        columns: [
            { data: 'order_link', name: 'order_number', orderable: true, searchable: true },
            { data: 'customer_name', name: 'customer_name', orderable: true, searchable: true },
            { data: 'total', name: 'total', orderable: true, searchable: false },
            { data: 'status_badge', name: 'status', orderable: true, searchable: false },
            { data: 'created_at', name: 'created_at', orderable: true, searchable: false }
        ]
    });

    document.getElementById('orders-status-filter').addEventListener('change', function () {
        table.ajax.reload();
    });
});
</script>
@endpush
