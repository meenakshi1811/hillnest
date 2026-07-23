@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')
<a href="{{ route('admin.products.index') }}" class="admin-back-link">← Products</a>
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Add Product</h1>
        <p class="admin-page-header__subtitle">Create a new catalogue item</p>
    </div>
</div>

<form
    method="POST"
    action="{{ route('admin.products.store') }}"
    class="admin-card admin-form-card"
    data-product-form
    enctype="multipart/form-data"
    novalidate
>
    @include('admin.products._form')
</form>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-product-form.js') }}"></script>
@endpush
