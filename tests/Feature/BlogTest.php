<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\User;
use Database\Seeders\BlogPostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    private function makePost(array $attributes = []): BlogPost
    {
        return BlogPost::create(array_merge([
            'title' => 'What Is Bilona Ghee?',
            'slug' => 'what-is-bilona-ghee',
            'category' => 'Ghee Guide',
            'excerpt' => 'Bilona ghee is made from curd churned into butter.',
            'body' => "Intro paragraph.\n\n## How it is made\n\nSteps.\n\n## Texture\n\nGrainy.\n\n## Taste\n\nNutty.",
            'faqs' => [['question' => 'Is it grainy?', 'answer' => 'Yes, usually.']],
            'author_name' => 'HillNest',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ], $attributes));
    }

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_blog_index_lists_only_published_posts(): void
    {
        $this->makePost();
        $this->makePost(['slug' => 'draft-post', 'title' => 'Secret Draft', 'is_published' => false]);
        $this->makePost(['slug' => 'scheduled-post', 'title' => 'Future Post', 'published_at' => now()->addWeek()]);

        $this->get('/blog')
            ->assertOk()
            ->assertSee('What Is Bilona Ghee?')
            ->assertDontSee('Secret Draft')
            ->assertDontSee('Future Post');
    }

    public function test_published_post_has_seo_markup(): void
    {
        $this->makePost(['meta_title' => 'Bilona Ghee Explained', 'meta_description' => 'A clear guide.']);

        $response = $this->get('/blog/what-is-bilona-ghee')->assertOk();

        $response->assertSee('<title>Bilona Ghee Explained</title>', false)
            ->assertSee('<meta name="description" content="A clear guide." />', false)
            ->assertSee('<link rel="canonical" href="'.rtrim(config('app.url'), '/').'/blog/what-is-bilona-ghee" />', false)
            ->assertSee('"@type":"BlogPosting"', false)
            ->assertSee('"@type":"FAQPage"', false)
            ->assertSee('"@type":"BreadcrumbList"', false)
            ->assertSee('<h2 id="how-it-is-made">', false)
            ->assertSee('In this article')
            ->assertDontSee('noindex');
    }

    public function test_markdown_html_is_stripped(): void
    {
        $this->makePost(['body' => "Safe text.\n\n<script>alert(1)</script>\n\n[bad](javascript:alert(1))"]);

        $this->get('/blog/what-is-bilona-ghee')
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertDontSee('javascript:alert', false);
    }

    public function test_drafts_are_hidden_from_visitors_but_visible_to_admins(): void
    {
        $this->makePost(['is_published' => false, 'published_at' => null]);

        $this->get('/blog/what-is-bilona-ghee')->assertNotFound();

        $this->actingAs($this->admin())
            ->get('/blog/what-is-bilona-ghee')
            ->assertOk()
            ->assertSee('Admin preview')
            ->assertSee('<meta name="robots" content="noindex" />', false);
    }

    public function test_sitemap_includes_published_posts_only(): void
    {
        $this->makePost();
        $this->makePost(['slug' => 'draft-post', 'is_published' => false]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/blog/what-is-bilona-ghee', false)
            ->assertSee('/blog</loc>', false)
            ->assertDontSee('/blog/draft-post', false);
    }

    public function test_paginated_blog_pages_keep_their_own_canonical(): void
    {
        foreach (range(1, 10) as $i) {
            $this->makePost(['slug' => "post-$i", 'title' => "Post $i", 'published_at' => now()->subDays($i)]);
        }

        $this->get('/blog?page=2')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.rtrim(config('app.url'), '/').'/blog?page=2" />', false);
    }

    public function test_admin_can_create_update_and_toggle_a_post(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->postJson('/admin/blog', [
                'title' => 'Ghee in Winter',
                'excerpt' => 'Seven traditional ways to use ghee.',
                'body' => str_repeat('Warm winter food with ghee. ', 5),
                'author_name' => 'Meenakshi, HillNest',
                'is_published' => '1',
                'faqs' => [
                    ['question' => 'Does ghee set in winter?', 'answer' => 'Yes.'],
                    ['question' => '', 'answer' => ''],
                ],
            ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $post = BlogPost::firstWhere('slug', 'ghee-in-winter');
        $this->assertNotNull($post);
        $this->assertTrue($post->isLive());
        $this->assertCount(1, $post->faqs);
        $this->assertStringEndsWith('/admin/blog/'.$post->id.'/edit', route('admin.blog.edit', $post));

        $this->actingAs($admin)
            ->patchJson('/admin/blog/'.$post->id, [
                'title' => 'Ghee in Winter',
                'slug' => 'ghee-in-winter-guide',
                'excerpt' => 'Updated.',
                'body' => str_repeat('Updated body text here. ', 5),
                'author_name' => 'HillNest',
                'is_published' => '1',
            ])
            ->assertOk()
            ->assertJsonPath('slug', 'ghee-in-winter-guide');

        $this->actingAs($admin)->patchJson('/admin/blog/'.$post->id.'/toggle')->assertJsonPath('is_published', false);

        // Back to a logged-out visitor: the draft is hidden again.
        $this->app['auth']->forgetGuards();
        $this->get('/blog/ghee-in-winter-guide')->assertNotFound();
    }

    public function test_admin_validation_rejects_bad_slug(): void
    {
        $this->actingAs($this->admin())
            ->postJson('/admin/blog', [
                'title' => 'Bad',
                'slug' => 'Bad Slug!',
                'excerpt' => 'x',
                'body' => str_repeat('text ', 20),
                'author_name' => 'HillNest',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('slug');
    }

    public function test_admin_pages_render(): void
    {
        $post = $this->makePost();
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/blog')->assertOk();
        $this->actingAs($admin)->get('/admin/blog/create')->assertOk();
        $this->actingAs($admin)->get('/admin/blog/'.$post->id.'/edit')->assertOk()->assertSee('What Is Bilona Ghee?');
        $this->actingAs($admin)
            ->getJson('/admin/blog?draw=1&start=0&length=10', ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertJsonPath('recordsTotal', 1);
    }

    public function test_seeder_creates_drafts_and_does_not_overwrite_edits(): void
    {
        $this->seed(BlogPostSeeder::class);

        $this->assertSame(6, BlogPost::count());
        $this->assertSame(0, BlogPost::where('is_published', true)->count());

        BlogPost::where('slug', 'what-is-bilona-ghee')->update(['title' => 'Edited by the team']);
        $this->seed(BlogPostSeeder::class);

        $this->assertSame(6, BlogPost::count());
        $this->assertSame('Edited by the team', BlogPost::firstWhere('slug', 'what-is-bilona-ghee')->title);

        foreach (BlogPost::all() as $post) {
            $this->assertLessThanOrEqual(70, mb_strlen($post->meta_title), $post->slug.' meta title too long');
            $this->assertLessThanOrEqual(170, mb_strlen($post->meta_description), $post->slug.' meta description too long');
            $this->assertLessThanOrEqual(300, mb_strlen($post->excerpt), $post->slug.' excerpt too long');
            $this->assertNotEmpty($post->renderedBody()['toc'], $post->slug.' has no sections');

            // Every article ships with a cover and at least one in-article image, and the files exist.
            $this->assertFileExists(public_path($post->cover_image), $post->slug.' cover missing');
            $this->assertNotEmpty($post->cover_image_alt);
            preg_match_all('/!\[([^\]]+)\]\(([^)]+)\)/', $post->body, $images, PREG_SET_ORDER);
            $this->assertNotEmpty($images, $post->slug.' has no in-article image');
            foreach ($images as [, $alt, $src]) {
                $this->assertFileExists(public_path(ltrim($src, '/')), $post->slug.' image missing: '.$src);
                $this->assertGreaterThan(20, mb_strlen($alt), $post->slug.' image alt text too short');
            }
            $this->assertMatchesRegularExpression('/<a href="\/images\/blog\/[^"]+" class="blog-zoom"[^>]*><img src="\/images\/blog\/[^"]+" alt="[^"]+" loading="lazy" decoding="async" width="1200" height="\d+"><\/a>/', $post->renderedBody()['html']);
            foreach ($post->renderedBody()['toc'] as $item) {
                $this->assertDoesNotMatchRegularExpression('/^\d+[.)]\s/', $item['text'], $post->slug.' TOC keeps its own numbering');
            }
        }
    }
}
