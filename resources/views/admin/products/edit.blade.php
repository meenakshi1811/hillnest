@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<a href="{{ route('admin.products.index') }}" class="admin-back-link">← Products</a>
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Edit Product</h1>
        <p class="admin-page-header__subtitle" id="product-form-subtitle">{{ $product->name }}</p>
    </div>
</div>

<form
    method="POST"
    action="{{ route('admin.products.update', $product) }}"
    class="admin-card admin-form-card"
    data-product-form
    enctype="multipart/form-data"
    novalidate
>
    @include('admin.products._form', ['product' => $product])
</form>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-product-form.js') }}"></script>
@endpush
