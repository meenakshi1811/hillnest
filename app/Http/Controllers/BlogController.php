<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Product;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::published()->latest('published_at')->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function show(Request $request, BlogPost $post)
    {
        // Drafts and scheduled posts are visible to admins only, so they can check them before publishing.
        abort_unless($post->isLive() || $request->user()?->isAdmin(), 404);

        $content = $post->renderedBody();

        $related = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->orderByRaw('CASE WHEN category = ? THEN 0 ELSE 1 END', [$post->category])
            ->latest('published_at')
            ->limit(3)
            ->get();

        $products = Product::active()->orderBy('sort_order')->limit(3)->get();

        return view('blog.show', compact('post', 'content', 'related', 'products'));
    }
}
