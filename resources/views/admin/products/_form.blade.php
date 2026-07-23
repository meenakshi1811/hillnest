@csrf
@if(isset($product))
    @method('PATCH')
@endif

<div class="admin-form-grid">
    <div class="admin-field admin-field--full">
        <label class="admin-label" for="image">Product Image</label>
        <div class="admin-image-upload">
            <div class="admin-image-preview" id="product-image-preview-wrap">
                <img
                    src="{{ isset($product) ? $product->image_url : '' }}"
                    alt="Product preview"
                    id="product-image-preview"
                    class="admin-image-preview__img"
                    @if(!isset($product)) hidden @endif
                >
                <div class="admin-image-preview__placeholder" id="product-image-placeholder" @if(isset($product)) hidden @endif>
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                    <span>No image selected</span>
                </div>
            </div>
            <div class="admin-image-upload__controls">
                <label for="image" class="admin-file-label">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Choose image
                </label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/jpg" class="admin-file-input">
                <p class="admin-field__hint">JPG, PNG or WebP · max 4MB</p>
                <p class="admin-field__error" data-field-error="image" hidden></p>
            </div>
        </div>
    </div>

    <div class="admin-field">
        <label class="admin-label" for="name">Name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required class="admin-input">
        <p class="admin-field__error" data-field-error="name" hidden></p>
    </div>

    <div class="admin-field">
        <label class="admin-label" for="short_description">Short Description</label>
        <input type="text" id="short_description" name="short_description" value="{{ old('short_description', $product->short_description ?? '') }}" class="admin-input">
        <p class="admin-field__error" data-field-error="short_description" hidden></p>
    </div>

    <div class="admin-field">
        <label class="admin-label" for="description">Description</label>
        <textarea id="description" name="description" rows="5" required class="admin-textarea">{{ old('description', $product->description ?? '') }}</textarea>
        <p class="admin-field__error" data-field-error="description" hidden></p>
    </div>

    <div class="admin-form-grid admin-form-grid--2">
        <div class="admin-field">
            <label class="admin-label" for="price">Price (₹)</label>
            <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $product->price ?? '') }}" required class="admin-input">
            <p class="admin-field__error" data-field-error="price" hidden></p>
        </div>
        <div class="admin-field">
            <label class="admin-label" for="compare_price">Compare Price</label>
            <input type="number" step="0.01" id="compare_price" name="compare_price" value="{{ old('compare_price', $product->compare_price ?? '') }}" class="admin-input">
            <p class="admin-field__error" data-field-error="compare_price" hidden></p>
        </div>
    </div>

    <div class="admin-form-grid admin-form-grid--2">
        <div class="admin-field">
            <label class="admin-label" for="size">Size</label>
            <input type="text" id="size" name="size" value="{{ old('size', $product->size ?? '') }}" class="admin-input" placeholder="e.g. 250gm">
            <p class="admin-field__error" data-field-error="size" hidden></p>
        </div>
        <div class="admin-field">
            <label class="admin-label" for="stock">Stock</label>
            <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock ?? 100) }}" required class="admin-input">
            <p class="admin-field__error" data-field-error="stock" hidden></p>
        </div>
    </div>

    <div class="admin-check-row">
        <label class="admin-check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))> Active</label>
        <label class="admin-check"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured ?? false))> Featured</label>
        <label class="admin-check"><input type="checkbox" name="is_bestseller" value="1" @checked(old('is_bestseller', $product->is_bestseller ?? false))> Best Seller</label>
        <label class="admin-check"><input type="checkbox" name="is_trending" value="1" @checked(old('is_trending', $product->is_trending ?? false))> Trending</label>
    </div>

    <div>
        <button type="submit" class="admin-btn" data-product-submit>
            <span class="admin-btn__loader" aria-hidden="true"></span>
            <span class="admin-btn__text">{{ isset($product) ? 'Save Product' : 'Add Product' }}</span>
        </button>
    </div>
</div>
