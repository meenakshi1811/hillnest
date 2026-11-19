<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $fillable = [
        'title', 'slug', 'category', 'excerpt', 'body', 'faqs', 'cover_image', 'cover_image_alt',
        'meta_title', 'meta_description', 'author_name', 'is_published', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'faqs' => 'array',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Live on the site: switched on and the publish time has arrived. */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function isLive(): bool
    {
        return $this->is_published && $this->published_at && $this->published_at->lte(now());
    }

    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: $this->title.' — HillNest';
    }

    public function getSeoDescriptionAttribute(): string
    {
        return $this->meta_description ?: $this->excerpt;
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        if (! $this->cover_image) {
            return null;
        }

        if (str_starts_with($this->cover_image, 'http')) {
            return $this->cover_image;
        }

        return file_exists(public_path($this->cover_image)) ? asset($this->cover_image) : null;
    }

    public function getReadingMinutesAttribute(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->body ?? '')) / 200));
    }

    /** FAQ rows that have both a question and an answer. */
    public function getFaqItemsAttribute(): array
    {
        return collect($this->faqs ?? [])
            ->filter(fn ($faq) => filled($faq['question'] ?? null) && filled($faq['answer'] ?? null))
            ->values()
            ->all();
    }

    /**
     * The article body as HTML plus a table of contents built from its ## headings.
     * Raw HTML in the Markdown is stripped, so admins can't inject scripts.
     *
     * @return array{html: string, toc: array<int, array{id: string, text: string}>}
     */
    public function renderedBody(): array
    {
        $html = Str::markdown($this->body ?? '', [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        $toc = [];
        $used = [];

        $html = preg_replace_callback('/<h2>(.*?)<\/h2>/s', function (array $match) use (&$toc, &$used) {
            $text = trim(html_entity_decode(strip_tags($match[1]), ENT_QUOTES | ENT_HTML5));
            $id = Str::slug($text) ?: 'section';

            if (isset($used[$id])) {
                $id .= '-'.(++$used[$id]);
            } else {
                $used[$id] = 1;
            }

            // The list is already numbered, so "## 1. The heat test" shows as "The heat test".
            $toc[] = ['id' => $id, 'text' => preg_replace('/^\d+[.)]\s+/', '', $text)];

            return '<h2 id="'.e($id).'">'.$match[1].'</h2>';
        }, $html);

        $html = preg_replace_callback('/<img src="([^"]+)"([^>]*?)\s*\/?>/', function (array $match) {
            $attributes = ' loading="lazy" decoding="async"';

            // Size images stored on this site so the page doesn't jump while they load.
            $path = parse_url(html_entity_decode($match[1]), PHP_URL_PATH);
            if ($path && str_starts_with($path, '/') && ! str_contains($path, '..')) {
                $file = public_path(ltrim($path, '/'));
                if (is_file($file) && ($size = @getimagesize($file))) {
                    $attributes .= ' width="'.$size[0].'" height="'.$size[1].'"';

                    // Our own images open full size in a new tab, for zooming on a phone.
                    return '<a href="'.$match[1].'" class="blog-zoom" target="_blank" rel="noopener">'
                        .'<img src="'.$match[1].'"'.$match[2].$attributes.'></a>';
                }
            }

            return '<img src="'.$match[1].'"'.$match[2].$attributes.'>';
        }, $html);

        return ['html' => $html, 'toc' => $toc];
    }

    public static function uniqueSlug(string $source, ?int $ignoreId = null): string
    {
        $slug = Str::slug($source) ?: 'post';
        $original = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $original.'-'.(++$counter);
        }

        return $slug;
    }
}
