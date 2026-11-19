@extends('layouts.admin')

@section('title', 'Edit Article')

@section('content')
<a href="{{ route('admin.blog.index') }}" class="admin-back-link">← Blog</a>
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Edit Article</h1>
        <p class="admin-page-header__subtitle" id="blog-form-subtitle">{{ $post->title }}</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.blog.update', $post) }}" class="admin-card admin-form-card" data-blog-form enctype="multipart/form-data" novalidate>
    @include('admin.blog._form', ['post' => $post])
</form>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-blog-form.js') }}?v={{ filemtime(public_path('js/admin-blog-form.js')) }}"></script>
@endpush
