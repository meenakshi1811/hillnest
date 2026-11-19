<article class="blog-card">
    <a href="{{ route('blog.show', $post) }}" class="blog-card__media" tabindex="-1" aria-hidden="true">
        @if($post->cover_image_url)
            <img src="{{ $post->cover_image_url }}" alt="" @if(empty($eager)) loading="lazy" @endif decoding="async">
        @else
            <span class="blog-card__placeholder">{{ $post->category ?: 'HillNest' }}</span>
        @endif
    </a>
    <div class="blog-card__body">
        @if($post->category)
            <p class="blog-card__category">{{ $post->category }}</p>
        @endif
        <h2 class="blog-card__title"><a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a></h2>
        <p class="blog-card__excerpt">{{ $post->excerpt }}</p>
        <p class="blog-card__meta">
            @if($post->published_at)
                <time datetime="{{ $post->published_at->toAtomString() }}">{{ $post->published_at->timezone('Asia/Kolkata')->format('j M Y') }}</time> ·
            @endif
            {{ $post->reading_minutes }} min read
        </p>
    </div>
</article>
