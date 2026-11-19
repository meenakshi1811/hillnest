<?php

namespace App\DataTables;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Facades\DataTables;

class BlogPostsDataTable
{
    private const TZ = 'Asia/Kolkata';

    public function query(): Builder
    {
        return BlogPost::query()->select([
            'id', 'title', 'slug', 'category', 'cover_image', 'is_published', 'published_at', 'updated_at',
        ]);
    }

    public function json()
    {
        return DataTables::eloquent($this->query())
            ->addColumn('post_cell', function (BlogPost $post) {
                $category = $post->category
                    ? '<span class="admin-badge admin-badge--gold">'.e($post->category).'</span> '
                    : '';

                return '
                    <div>
                        <p class="admin-product-cell__name">'.e($post->title).'</p>
                        <p class="admin-table__muted">'.$category.'/blog/'.e($post->slug).'</p>
                    </div>';
            })
            ->addColumn('status_toggle', function (BlogPost $post) {
                $checked = $post->is_published ? 'checked' : '';
                $label = match (true) {
                    ! $post->is_published => 'Draft',
                    $post->isLive() => 'Published',
                    default => 'Scheduled',
                };

                return '
                    <label class="admin-toggle" title="'.($post->is_published ? 'Click to move to drafts' : 'Click to publish now').'">
                        <input type="checkbox" class="admin-toggle__input js-blog-toggle" data-url="'.route('admin.blog.toggle', $post).'" '.$checked.'>
                        <span class="admin-toggle__track" aria-hidden="true"></span>
                        <span class="admin-toggle__label">'.$label.'</span>
                    </label>';
            })
            ->editColumn('published_at', fn (BlogPost $post) => $post->published_at
                ? e($post->published_at->timezone(self::TZ)->format('d M Y, h:i A'))
                : '<span class="admin-table__muted">—</span>')
            ->editColumn('updated_at', fn (BlogPost $post) => e($post->updated_at->timezone(self::TZ)->format('d M Y')))
            ->addColumn('action', function (BlogPost $post) {
                $viewUrl = route('blog.show', $post);
                $editUrl = route('admin.blog.edit', $post);
                $deleteUrl = route('admin.blog.destroy', $post);

                return '
                    <div class="admin-row-actions">
                        <a href="'.$viewUrl.'" class="admin-icon-btn" target="_blank" rel="noopener" title="View article (drafts are visible to admins only)" aria-label="View article">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <a href="'.$editUrl.'" class="admin-icon-btn" title="Edit article" aria-label="Edit article">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                        </a>
                        <button type="button" class="admin-icon-btn admin-icon-btn--danger js-blog-delete" data-url="'.$deleteUrl.'" data-name="'.e($post->title).'" title="Delete article" aria-label="Delete article">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        </button>
                    </div>';
            })
            ->filterColumn('title', fn ($query, $keyword) => $query->where(fn ($q) => $q
                ->where('title', 'like', "%{$keyword}%")
                ->orWhere('slug', 'like', "%{$keyword}%")
                ->orWhere('category', 'like', "%{$keyword}%")))
            ->rawColumns(['post_cell', 'status_toggle', 'published_at', 'action'])
            ->toJson();
    }
}
