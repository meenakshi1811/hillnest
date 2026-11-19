@csrf
@isset($post)
    @method('PATCH')
@endisset

@php
    $faqRows = old('faqs', $post->faqs ?? []);
    $publishedAt = old('published_at', isset($post) && $post->published_at ? $post->published_at->timezone(config('app.timezone'))->format('Y-m-d\TH:i') : '');
@endphp

<div class="admin-form-grid">
    <div class="admin-field admin-field--full">
        <label class="admin-label" for="title">Title</label>
        <input type="text" id="title" name="title" value="{{ old('title', $post->title ?? '') }}" required maxlength="160" class="admin-input" placeholder="e.g. What Is Bilona Ghee? How It's Made and Why It Tastes Different">
        <p class="admin-field__hint">The H1 of the page. Put the main search phrase near the start.</p>
        <p class="admin-field__error" data-field-error="title" hidden></p>
    </div>

    <div class="admin-form-grid admin-form-grid--2">
        <div class="admin-field">
            <label class="admin-label" for="slug">URL slug</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $post->slug ?? '') }}" maxlength="180" class="admin-input" placeholder="what-is-bilona-ghee" data-slug-input>
            <p class="admin-field__hint">hillnest.co/blog/<strong data-slug-preview>{{ $post->slug ?? 'auto-from-title' }}</strong>. Leave empty to build it from the title. Avoid changing it after publishing: old links would break.</p>
            <p class="admin-field__error" data-field-error="slug" hidden></p>
        </div>
        <div class="admin-field">
            <label class="admin-label" for="category">Category</label>
            <input type="text" id="category" name="category" value="{{ old('category', $post->category ?? '') }}" maxlength="60" class="admin-input" list="blog-categories" placeholder="Ghee Guide">
            <datalist id="blog-categories">
                @foreach($categories as $category)
                    <option value="{{ $category }}">
                @endforeach
            </datalist>
            <p class="admin-field__error" data-field-error="category" hidden></p>
        </div>
    </div>

    <div class="admin-field admin-field--full">
        <label class="admin-label" for="excerpt">Summary <span class="admin-char-count" data-count-for="excerpt" data-ideal="150-160" data-max="300"></span></label>
        <textarea id="excerpt" name="excerpt" rows="2" required maxlength="300" class="admin-textarea admin-textarea--compact" placeholder="One or two sentences shown on the blog list and used as the Google description when the SEO description is empty.">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
        <p class="admin-field__error" data-field-error="excerpt" hidden></p>
    </div>

    <div class="admin-field admin-field--full">
        <label class="admin-label" for="body">Article (Markdown)</label>
        <textarea id="body" name="body" rows="22" required class="admin-textarea admin-blog-body">{{ old('body', $post->body ?? '') }}</textarea>
        <details class="admin-blog-help">
            <summary>Formatting help</summary>
            <div>
                <p><code>## Heading</code> section heading (also builds the table of contents) · <code>### Sub-heading</code></p>
                <p><code>**bold**</code> · <code>*italic*</code> · <code>- item</code> bullet list · <code>1. item</code> numbered list · <code>&gt; quote</code></p>
                <p><code>[link text](/shop/hill-nest-pure-bilona-ghee-500gm)</code> link to a page on this site · <code>![photo description](/images/blog/photo.jpg)</code> image</p>
                <p>Leave an empty line between paragraphs. HTML is removed for safety.</p>
            </div>
        </details>
        <p class="admin-field__error" data-field-error="body" hidden></p>
    </div>

    <div class="admin-field admin-field--full">
        <span class="admin-label">FAQs <span class="admin-field__hint">(shown at the end of the article and sent to Google as FAQ data)</span></span>
        <div class="admin-faq-list" data-faq-list>
            @foreach($faqRows as $i => $faq)
                <div class="admin-faq-row" data-faq-row>
                    <input type="text" name="faqs[{{ $i }}][question]" value="{{ $faq['question'] ?? '' }}" maxlength="200" class="admin-input" placeholder="Question">
                    <textarea name="faqs[{{ $i }}][answer]" rows="2" maxlength="1000" class="admin-textarea admin-textarea--compact" placeholder="Answer">{{ $faq['answer'] ?? '' }}</textarea>
                    <button type="button" class="admin-icon-btn admin-icon-btn--danger" data-faq-remove aria-label="Remove question">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>
            @endforeach
        </div>
        <template data-faq-template>
            <div class="admin-faq-row" data-faq-row>
                <input type="text" name="faqs[__i__][question]" maxlength="200" class="admin-input" placeholder="Question">
                <textarea name="faqs[__i__][answer]" rows="2" maxlength="1000" class="admin-textarea admin-textarea--compact" placeholder="Answer"></textarea>
                <button type="button" class="admin-icon-btn admin-icon-btn--danger" data-faq-remove aria-label="Remove question">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>
        </template>
        <div>
            <button type="button" class="admin-btn admin-btn--sm admin-btn--ghost" data-faq-add>+ Add question</button>
        </div>
        <p class="admin-field__error" data-field-error="faqs" hidden></p>
    </div>

    <div class="admin-field admin-field--full">
        <label class="admin-label" for="cover_image">Cover photo</label>
        <div class="admin-image-upload">
            <div class="admin-image-preview">
                <img src="{{ isset($post) ? $post->cover_image_url : '' }}" alt="Cover preview" id="cover-image-preview" class="admin-image-preview__img" @if(! isset($post) || ! $post->cover_image_url) hidden @endif>
                <div class="admin-image-preview__placeholder" id="cover-image-placeholder" @if(isset($post) && $post->cover_image_url) hidden @endif>
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                    <span>No photo yet</span>
                </div>
            </div>
            <div class="admin-image-upload__controls">
                <label for="cover_image" class="admin-file-label">Choose photo</label>
                <input type="file" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp" class="admin-file-input">
                <p class="admin-field__hint">Your own photo works best (village, bilona, kitchen). Landscape, at least 1200px wide · JPG, PNG or WebP · max 4MB.</p>
                @if(isset($post) && $post->cover_image)
                    <label class="admin-check"><input type="checkbox" name="remove_cover_image" value="1"> Remove current photo</label>
                @endif
                <p class="admin-field__error" data-field-error="cover_image" hidden></p>
            </div>
        </div>
    </div>

    <div class="admin-field admin-field--full">
        <label class="admin-label" for="cover_image_alt">Photo description (alt text)</label>
        <input type="text" id="cover_image_alt" name="cover_image_alt" value="{{ old('cover_image_alt', $post->cover_image_alt ?? '') }}" maxlength="160" class="admin-input" placeholder="e.g. Curd being churned in a wooden bilona in Chhajpur">
        <p class="admin-field__error" data-field-error="cover_image_alt" hidden></p>
    </div>

    <div class="admin-blog-seo">
        <p class="admin-blog-seo__title">Google search preview</p>
        <div class="admin-serp" aria-hidden="true">
            <p class="admin-serp__url">hillnest.co › blog › <span data-serp-slug>{{ $post->slug ?? '…' }}</span></p>
            <p class="admin-serp__title" data-serp-title></p>
            <p class="admin-serp__desc" data-serp-desc></p>
        </div>

        <div class="admin-form-grid admin-form-grid--2">
            <div class="admin-field">
                <label class="admin-label" for="meta_title">SEO title <span class="admin-char-count" data-count-for="meta_title" data-ideal="50-60" data-max="70"></span></label>
                <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $post->meta_title ?? '') }}" maxlength="70" class="admin-input" placeholder="Empty = article title + “— HillNest”">
                <p class="admin-field__error" data-field-error="meta_title" hidden></p>
            </div>
            <div class="admin-field">
                <label class="admin-label" for="meta_description">SEO description <span class="admin-char-count" data-count-for="meta_description" data-ideal="140-160" data-max="170"></span></label>
                <textarea id="meta_description" name="meta_description" rows="2" maxlength="170" class="admin-textarea admin-textarea--compact" placeholder="Empty = the summary above">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                <p class="admin-field__error" data-field-error="meta_description" hidden></p>
            </div>
        </div>
    </div>

    <div class="admin-form-grid admin-form-grid--2">
        <div class="admin-field">
            <label class="admin-label" for="author_name">Written by</label>
            <input type="text" id="author_name" name="author_name" value="{{ old('author_name', $post->author_name ?? 'HillNest') }}" required maxlength="80" class="admin-input" placeholder="e.g. Meenakshi, HillNest">
            <p class="admin-field__error" data-field-error="author_name" hidden></p>
        </div>
        <div class="admin-field">
            <label class="admin-label" for="published_at">Publish date</label>
            <input type="datetime-local" id="published_at" name="published_at" value="{{ $publishedAt }}" class="admin-input">
            <p class="admin-field__hint">Empty = now when published. A future date schedules it.</p>
            <p class="admin-field__error" data-field-error="published_at" hidden></p>
        </div>
    </div>

    <div class="admin-check-row">
        <label class="admin-check"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published ?? false))> Published (unticked = draft, visible to admins only)</label>
    </div>

    <div class="admin-blog-actions">
        <button type="submit" class="admin-btn" data-blog-submit>
            <span class="admin-btn__loader" aria-hidden="true"></span>
            <span class="admin-btn__text">{{ isset($post) ? 'Save Article' : 'Create Article' }}</span>
        </button>
        @isset($post)
            <a href="{{ route('blog.show', $post) }}" class="admin-btn admin-btn--ghost" target="_blank" rel="noopener" data-view-link>View on site ↗</a>
        @endisset
    </div>
</div>
