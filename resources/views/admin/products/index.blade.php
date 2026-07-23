@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Products</h1>
        <p class="admin-page-header__subtitle">Manage catalogue and inventory</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn--sm">+ Add Product</a>
</div>

<div class="admin-table-wrap admin-table-wrap--scroll">
    <table id="products-table" class="admin-table admin-dt" style="width:100%">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Tags</th>
                <th>Active</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-products.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.productsTable = AdminDT.init('#products-table', {
        ajax: '{{ route('admin.products.index') }}',
        order: [[0, 'asc']],
        columns: [
            { data: 'product_cell', name: 'name', orderable: true, searchable: true },
            { data: 'price', name: 'price', orderable: true, searchable: false },
            { data: 'stock', name: 'stock', orderable: true, searchable: false },
            { data: 'product_tags', name: 'is_bestseller', orderable: false, searchable: false },
            { data: 'status_toggle', name: 'is_active', orderable: true, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'dt-right' }
        ]
    });
});
</script>
@endpush
