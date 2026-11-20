@props(['product', 'showQuantity' => false, 'variant' => 'card'])

@php
    $inCart = in_array($product->id, $cartProductIds ?? [], true);
    $cartQty = ($cartQuantities ?? [])[$product->id] ?? 1;
    $btnClass = $variant === 'page' ? 'product-detail__cart-btn product-detail__cart-btn--add' : 'product-card-rosier__btn';
    $removeClass = $variant === 'page' ? 'product-detail__cart-btn product-detail__cart-btn--remove' : 'product-card-rosier__btn product-card-rosier__btn--remove';
@endphp

<div
    class="cart-action {{ $inCart ? 'cart-action--in-cart' : '' }} {{ $variant === 'page' ? 'cart-action--page product-detail__cart' : 'product-card-rosier__form' }}"
    data-cart-action
    data-product-id="{{ $product->id }}"
    data-product-name="{{ $product->name }}"
    data-product-price="{{ $product->price }}"
    data-add-url="{{ route('cart.add', $product) }}"
    data-remove-url="{{ route('cart.remove', $product) }}"
>
    @if($showQuantity)
        <div class="product-detail__qty">
            <label class="product-detail__qty-label" for="qty-{{ $product->id }}">Quantity</label>
            <div class="qty-stepper">
                <button type="button" class="qty-stepper__btn" data-qty-step="-1" aria-label="Decrease quantity">−</button>
                <input
                    id="qty-{{ $product->id }}"
                    type="number"
                    name="quantity"
                    value="{{ $inCart ? $cartQty : 1 }}"
                    min="1"
                    max="20"
                    inputmode="numeric"
                    class="product-detail__qty-input"
                >
                <button type="button" class="qty-stepper__btn" data-qty-step="1" aria-label="Increase quantity">+</button>
            </div>
        </div>
    @endif

    @if($showQuantity)
        @once
            @push('scripts')
            <script>
            document.addEventListener('click', function (e) {
              var btn = e.target.closest('[data-qty-step]');
              if (!btn) return;
              var input = btn.parentElement.querySelector('input[name="quantity"]');
              if (!input) return;
              var min = parseInt(input.min, 10) || 1, max = parseInt(input.max, 10) || 99;
              var next = Math.min(max, Math.max(min, (parseInt(input.value, 10) || min) + parseInt(btn.dataset.qtyStep, 10)));
              input.value = next;
              input.dispatchEvent(new Event('change', { bubbles: true }));
            });
            </script>
            @endpush
        @endonce
    @endif

    <div class="cart-action__buttons {{ $variant === 'page' ? 'product-detail__cart-actions' : '' }}">
        @if($inCart)
            <span class="cart-action__status">✓ In your cart</span>
            <button type="button" class="{{ $removeClass }}" data-cart-remove>
                <span>Remove from Cart</span>
            </button>
        @else
            <button type="button" class="{{ $btnClass }}" data-cart-add>
                <span>Add to Cart</span>
            </button>
        @endif
    </div>
</div>
