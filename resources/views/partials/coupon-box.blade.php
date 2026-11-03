<div
    class="checkout-coupon"
    data-checkout-coupon
    data-apply-url="{{ route('checkout.coupon.apply') }}"
    data-remove-url="{{ route('checkout.coupon.remove') }}"
>
    <label class="checkout-label" for="coupon_code">Coupon code</label>
    <div class="checkout-coupon__row">
        <input
            class="checkout-input"
            type="text"
            id="coupon_code"
            name="coupon_code"
            value="{{ old('coupon_code', $appliedCoupon?->code) }}"
            placeholder="Enter your coupon"
            autocomplete="off"
            @if($appliedCoupon) readonly @endif
            data-coupon-input
        >
        @if($appliedCoupon)
            <button type="button" class="checkout-coupon__btn checkout-coupon__btn--remove" data-coupon-remove>Remove</button>
        @else
            <button type="button" class="checkout-coupon__btn" data-coupon-apply>Apply</button>
        @endif
    </div>
    <p class="checkout-field-error" data-coupon-error @unless($errors->has('coupon_code')) hidden @endunless>{{ $errors->first('coupon_code') }}</p>
    @if($appliedCoupon)
    <p class="checkout-coupon__applied">
        <span>{{ $appliedCoupon->code }}</span> applied — {{ $appliedCoupon->value_label }} off
    </p>
    @endif

    @if(($availableCoupons ?? collect())->isNotEmpty())
    <div class="coupon-offers">
        <p class="coupon-offers__title">Available offers</p>
        <ul class="coupon-offers__list">
            @foreach($availableCoupons as $offer)
            <li>
                <button
                    type="button"
                    class="coupon-offer {{ $appliedCoupon?->is($offer) ? 'is-applied' : '' }}"
                    data-coupon-pick="{{ $offer->code }}"
                    aria-label="Apply coupon {{ $offer->code }}"
                >
                    <span class="coupon-offer__code">{{ $offer->code }}</span>
                    <span class="coupon-offer__desc">
                        {{ $offer->value_label }} off your order
                        {{-- Coupons stop working at the start of their expiry date, so the last full day is the day before. --}}
                        @if($offer->expires_at)
                            <small>Valid till {{ $offer->expires_at->copy()->subSecond()->format('d M Y') }}</small>
                        @endif
                    </span>
                    <span class="coupon-offer__action" data-coupon-pick-label>{{ $appliedCoupon?->is($offer) ? 'Applied' : 'Apply' }}</span>
                </button>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
