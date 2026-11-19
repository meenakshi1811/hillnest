<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BlogPostsDataTable;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    private const IMAGE_DIR = 'images/blog';

    public function index(Request $request, BlogPostsDataTable $dataTable)
    {
        if ($request->ajax()) {
            return $dataTable->json();
        }

        return view('admin.blog.index');
    }

    public function create()
    {
        return view('admin.blog.create', ['categories' => $this->categories()]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);

        $post = BlogPost::create(array_merge($this->payload($request, $data), [
            'slug' => BlogPost::uniqueSlug(($data['slug'] ?? null) ?: $data['title']),
            'cover_image' => $request->hasFile('cover_image')
                ? $this->storeImage($request->file('cover_image'), $data['title'])
                : null,
        ]));

        return response()->json([
            'success' => true,
            'message' => $post->is_published ? 'Article published.' : 'Draft saved.',
            'redirect' => route('admin.blog.edit', $post),
        ]);
    }

    public function edit(BlogPost $blogPost)
    {
        return view('admin.blog.edit', ['post' => $blogPost, 'categories' => $this->categories()]);
    }

    public function update(Request $request, BlogPost $blogPost): JsonResponse
    {
        $data = $this->validated($request);
        $payload = $this->payload($request, $data, $blogPost);
        $payload['slug'] = BlogPost::uniqueSlug(($data['slug'] ?? null) ?: $data['title'], $blogPost->id);

        if ($request->hasFile('cover_image')) {
            $this->deleteImage($blogPost->cover_image);
            $payload['cover_image'] = $this->storeImage($request->file('cover_image'), $data['title']);
        } elseif ($request->boolean('remove_cover_image')) {
            $this->deleteImage($blogPost->cover_image);
            $payload['cover_image'] = null;
        }

        $blogPost->update($payload);

        return response()->json([
            'success' => true,
            'message' => 'Article saved.',
            'slug' => $blogPost->slug,
            'view_url' => route('blog.show', $blogPost),
            'cover_image_url' => $blogPost->cover_image_url,
            'is_live' => $blogPost->isLive(),
        ]);
    }

    public function togglePublished(BlogPost $blogPost): JsonResponse
    {
        $publish = ! $blogPost->is_published;

        $blogPost->update([
            'is_published' => $publish,
            'published_at' => $publish ? ($blogPost->published_at ?? now()) : $blogPost->published_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => $publish ? 'Article published.' : 'Article moved to drafts.',
            'is_published' => $blogPost->is_published,
        ]);
    }

    public function destroy(BlogPost $blogPost): JsonResponse
    {
        $this->deleteImage($blogPost->cover_image);
        $blogPost->delete();

        return response()->json(['success' => true, 'message' => 'Article deleted.']);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:180', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'category' => ['nullable', 'string', 'max:60'],
            'excerpt' => ['required', 'string', 'max:300'],
            'body' => ['required', 'string', 'min:50'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:170'],
            'author_name' => ['required', 'string', 'max:80'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['boolean'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'cover_image_alt' => ['nullable', 'string', 'max:160'],
            'faqs' => ['nullable', 'array', 'max:12'],
            'faqs.*.question' => ['nullable', 'string', 'max:200'],
            'faqs.*.answer' => ['nullable', 'string', 'max:1000'],
        ], [
            'slug.regex' => 'Use lowercase letters, numbers and single hyphens only, e.g. what-is-bilona-ghee.',
        ]);
    }

    private function payload(Request $request, array $data, ?BlogPost $post = null): array
    {
        $publish = $request->boolean('is_published');
        $publishedAt = filled($data['published_at'] ?? null)
            ? Carbon::parse($data['published_at'], config('app.timezone'))
            : $post?->published_at;

        $faqs = collect($data['faqs'] ?? [])
            ->map(fn ($faq) => ['question' => trim($faq['question'] ?? ''), 'answer' => trim($faq['answer'] ?? '')])
            ->filter(fn ($faq) => $faq['question'] !== '' && $faq['answer'] !== '')
            ->values()
            ->all();

        return [
            'title' => $data['title'],
            'category' => ($data['category'] ?? null) ?: null,
            'excerpt' => $data['excerpt'],
            'body' => $data['body'],
            'faqs' => $faqs ?: null,
            'cover_image_alt' => ($data['cover_image_alt'] ?? null) ?: null,
            'meta_title' => ($data['meta_title'] ?? null) ?: null,
            'meta_description' => ($data['meta_description'] ?? null) ?: null,
            'author_name' => $data['author_name'],
            'is_published' => $publish,
            // Publishing without a date means "now"; a future date schedules the article.
            'published_at' => $publish ? ($publishedAt ?? now()) : $publishedAt,
        ];
    }

    private function categories(): array
    {
        return BlogPost::query()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category')
            ->merge(['Ghee Guide', 'Recipes', 'Our Village'])
            ->unique()
            ->values()
            ->all();
    }

    private function storeImage(UploadedFile $file, string $title): string
    {
        $directory = public_path(self::IMAGE_DIR);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = Str::limit(Str::slug($title), 60, '').'-'.time().'.'.$file->guessExtension();
        $file->move($directory, $filename);

        return self::IMAGE_DIR.'/'.$filename;
    }

    private function deleteImage(?string $path): void
    {
        if (! $path || ! str_starts_with($path, self::IMAGE_DIR.'/')) {
            return;
        }

        $fullPath = public_path($path);

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}
