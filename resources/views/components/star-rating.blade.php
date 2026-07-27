@props([
    'rating' => 0,
    'count' => null,
    'showValue' => false,
])

@php
    $rating = max(0, min(5, (float) $rating));
    $fullStars = (int) floor($rating);
    $hasHalf = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($hasHalf ? 1 : 0);
@endphp

<span {{ $attributes->merge(['class' => 'star-rating']) }} aria-label="{{ number_format($rating, 1) }} out of 5 stars">
    <span class="star-rating__stars" aria-hidden="true">
        @for ($i = 0; $i < $fullStars; $i++)<span class="star-rating__star star-rating__star--full">★</span>@endfor
        @if ($hasHalf)<span class="star-rating__star star-rating__star--half">★</span>@endif
        @for ($i = 0; $i < $emptyStars; $i++)<span class="star-rating__star star-rating__star--empty">★</span>@endfor
    </span>
    @if ($showValue)
        <span class="star-rating__value">{{ number_format($rating, 1) }}</span>
    @endif
    @if ($count !== null)
        <span class="star-rating__count">({{ number_format($count) }} {{ Str::plural('review', $count) }})</span>
    @endif
</span>
