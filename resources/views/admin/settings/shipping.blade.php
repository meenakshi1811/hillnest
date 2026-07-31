@extends('layouts.admin')

@section('title', 'Shipping Settings')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Shipping Settings</h1>
        <p class="admin-page-header__subtitle">Control shipping fees shown on cart and checkout</p>
    </div>
</div>

<div class="admin-expense-layout">
    <form
        method="POST"
        action="{{ route('admin.settings.shipping.update') }}"
        class="admin-card admin-expense-form-card"
        novalidate
    >
        @csrf
        @method('PATCH')

        <div class="admin-card__head admin-expense-form-card__head">
            <div>
                <h2 class="admin-card__title">Store Shipping</h2>
                <p class="admin-expense-form-card__subtitle">Changes apply immediately on the storefront</p>
            </div>
        </div>

        <div class="admin-form-grid admin-expense-form">
            <div class="admin-field">
                <label class="admin-label admin-label--checkbox">
                    <input
                        type="checkbox"
                        name="shipping_enabled"
                        value="1"
                        @checked(old('shipping_enabled', $shipping['enabled']))
                    >
                    <span>Charge shipping on orders</span>
                </label>
                <p class="admin-field__hint">When off, customers always see free shipping.</p>
            </div>

            <div class="admin-field">
                <label class="admin-label" for="shipping_fee">Flat shipping fee (₹)</label>
                <input
                    type="number"
                    step="0.01"
                    min="0"
                    id="shipping_fee"
                    name="shipping_fee"
                    value="{{ old('shipping_fee', $shipping['fee']) }}"
                    required
                    class="admin-input"
                >
                @error('shipping_fee')<p class="admin-field__error">{{ $message }}</p>@enderror
            </div>

            <div class="admin-field">
                <label class="admin-label" for="free_shipping_threshold">Free shipping above (₹)</label>
                <input
                    type="number"
                    step="0.01"
                    min="0"
                    id="free_shipping_threshold"
                    name="free_shipping_threshold"
                    value="{{ old('free_shipping_threshold', $shipping['free_threshold']) }}"
                    required
                    class="admin-input"
                >
                <p class="admin-field__hint">Set to 0 to disable the free-shipping progress bar and threshold.</p>
                @error('free_shipping_threshold')<p class="admin-field__error">{{ $message }}</p>@enderror
            </div>

            <div class="admin-expense-form__footer">
                <button type="submit" class="admin-btn admin-btn--block">
                    <span class="admin-btn__text">Save Shipping Settings</span>
                </button>
            </div>
        </div>
    </form>

    <div class="admin-card">
        <div class="admin-card__head">
            <h2 class="admin-card__title">How it works</h2>
        </div>
        <div class="admin-card__body" style="padding:20px 24px;color:var(--admin-muted,#5C4A34);line-height:1.7;">
            <p style="margin:0 0 12px">When shipping is enabled, the flat fee applies to non-empty carts unless the subtotal meets the free-shipping threshold.</p>
            <p style="margin:0">Order totals and stored <code>shipping_fee</code> values use these settings at checkout time.</p>
        </div>
    </div>
</div>
@endsection
