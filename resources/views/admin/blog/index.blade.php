@extends('layouts.admin')

@section('title', 'Blog')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Blog</h1>
        <p class="admin-page-header__subtitle">Articles for Google search. Drafts are visible to admins only.</p>
    </div>
    <a href="{{ route('admin.blog.create') }}" class="admin-btn admin-btn--sm">+ New Article</a>
</div>

<div class="admin-table-wrap admin-table-wrap--scroll">
    <table id="blog-table" class="admin-table admin-dt" style="width:100%">
        <thead>
            <tr>
                <th>Article</th>
                <th>Status</th>
                <th>Publish date</th>
                <th>Updated</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-blog.js') }}?v={{ filemtime(public_path('js/admin-blog.js')) }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.blogTable = AdminDT.init('#blog-table', {
        ajax: '{{ route('admin.blog.index') }}',
        order: [[3, 'desc']],
        columns: [
            { data: 'post_cell', name: 'title', orderable: true, searchable: true },
            { data: 'status_toggle', name: 'is_published', orderable: true, searchable: false },
            { data: 'published_at', name: 'published_at', orderable: true, searchable: false },
            { data: 'updated_at', name: 'updated_at', orderable: true, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'dt-right' }
        ]
    });
});
</script>
@endpush
