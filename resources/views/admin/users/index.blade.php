@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Customers</h1>
        <p class="admin-page-header__subtitle">Registered customer accounts</p>
    </div>
</div>

<div class="admin-table-wrap admin-table-wrap--scroll">
    <table id="users-table" class="admin-table admin-dt" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Orders</th>
                <th>Status</th>
                <th>Joined</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-users.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    AdminDT.init('#users-table', {
        ajax: '{{ route('admin.users.index') }}',
        order: [[5, 'desc']],
        columns: [
            { data: 'name_link', name: 'name', orderable: true, searchable: true },
            { data: 'email', name: 'email', orderable: true, searchable: true },
            { data: 'phone', name: 'phone', orderable: true, searchable: true },
            { data: 'orders_count', name: 'orders_count', orderable: true, searchable: false },
            { data: 'status_toggle', name: 'is_blocked', orderable: true, searchable: false },
            { data: 'created_at', name: 'created_at', orderable: true, searchable: false }
        ]
    });
});
</script>
@endpush
