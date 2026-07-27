@props(['item', 'review' => null])

<div class="order-review-card" id="review-item-{{ $item->id }}">
    <div class="order-review-card__product">
        <div class="order-review-card__thumb">
            @if($item->product?->image_url)
                <img src="{{ $item->product->image_url }}" alt="">
            @else
                <span aria-hidden="true">🫙</span>
            @endif
        </div>
        <div>
            <strong>{{ $item->product_name }}</strong>
            @if($item->product_size)
                <span>{{ $item->product_size }}</span>
            @endif
        </div>
    </div>

    @if($review)
        <div class="order-review-card__submitted">
            <div class="order-review-card__submitted-head">
                <span class="order-review-card__label">Your review</span>
                <x-star-rating :rating="$review->rating" />
            </div>
            @if($review->comment)
                <p class="order-review-card__comment">"{{ $review->comment }}"</p>
            @endif
            <p class="order-review-card__meta">Submitted {{ $review->created_at->format('d M Y') }}</p>
        </div>
    @else
        <form class="order-review-form" method="POST" action="{{ route('account.reviews.store', $item) }}">
            @csrf
            <fieldset class="order-review-form__rating">
                <legend>Rate this product</legend>
                <div class="order-review-form__stars">
                    @for($star = 5; $star >= 1; $star--)
                    <input
                        type="radio"
                        name="rating"
                        id="rating-{{ $item->id }}-{{ $star }}"
                        value="{{ $star }}"
                        {{ (int) old('rating') === $star ? 'checked' : '' }}
                        required
                    >
                    <label for="rating-{{ $item->id }}-{{ $star }}" title="{{ $star }} star{{ $star > 1 ? 's' : '' }}">★</label>
                    @endfor
                </div>
            </fieldset>

            <label class="order-review-form__comment-label" for="comment-{{ $item->id }}">Share your experience (optional)</label>
            <textarea
                id="comment-{{ $item->id }}"
                name="comment"
                rows="3"
                maxlength="1000"
                placeholder="How was the taste, aroma, and packaging?"
            >{{ old('comment') }}</textarea>

            @error('rating')
                <p class="order-review-form__error">{{ $message }}</p>
            @enderror
            @error('comment')
                <p class="order-review-form__error">{{ $message }}</p>
            @enderror

            <button type="submit" class="order-review-form__submit">Submit review</button>
        </form>
    @endif
</div>
