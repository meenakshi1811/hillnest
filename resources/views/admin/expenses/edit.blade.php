@extends('layouts.admin')

@section('title', 'Edit Expense')

@section('content')
<a href="{{ route('admin.expenses.index') }}" class="admin-back-link">← Expenses</a>
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Edit Expense</h1>
        <p class="admin-page-header__subtitle">{{ $expense->title }}</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.expenses.update', $expense) }}" class="admin-card admin-form-card" data-expense-form novalidate>
    @method('PATCH')
    @include('admin.expenses._form', ['expense' => $expense])
</form>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-expenses.js') }}"></script>
@endpush
