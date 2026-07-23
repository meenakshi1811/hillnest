@extends('layouts.admin')

@section('title', 'Edit Coupon')

@section('content')
<a href="{{ route('admin.coupons.index') }}" class="admin-back-link">← Coupons</a>
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Edit Coupon</h1>
        <p class="admin-page-header__subtitle">{{ $coupon->code }}</p>
    </div>
</div>

<div class="admin-card" style="max-width:640px">
    <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}" data-coupon-form novalidate>
        @csrf
        @method('PATCH')
        @include('admin.coupons._form')
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-coupons.js') }}"></script>
@endpush
