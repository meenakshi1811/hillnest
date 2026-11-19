@extends('layouts.admin')

@section('title', 'New Article')

@section('content')
<a href="{{ route('admin.blog.index') }}" class="admin-back-link">← Blog</a>
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">New Article</h1>
        <p class="admin-page-header__subtitle">Saved as a draft until you tick Published</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.blog.store') }}" class="admin-card admin-form-card" data-blog-form enctype="multipart/form-data" novalidate>
    @include('admin.blog._form')
</form>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-blog-form.js') }}?v={{ filemtime(public_path('js/admin-blog-form.js')) }}"></script>
@endpush
