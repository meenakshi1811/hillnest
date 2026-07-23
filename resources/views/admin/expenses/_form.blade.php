@csrf

@php
    $selectedPurchaser = old('purchased_by', $expense->purchased_by ?? 'meenakshi');
@endphp

<div class="admin-form-grid admin-expense-form">
    <div class="admin-field">
        <label class="admin-label" for="title">What was purchased?</label>
        <input type="text" id="title" name="title" value="{{ old('title', $expense->title ?? '') }}" required class="admin-input" placeholder="e.g. Glass jars, Domain renewal">
        <p class="admin-field__error" data-field-error="title" hidden></p>
    </div>

    <div class="admin-expense-form__pair">
        <div class="admin-field">
            <label class="admin-label" for="quantity">Quantity</label>
            <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $expense->quantity ?? 1) }}" min="1" required class="admin-input" data-expense-qty>
            <p class="admin-field__error" data-field-error="quantity" hidden></p>
        </div>
        <div class="admin-field">
            <label class="admin-label" for="unit_price">Unit price (₹)</label>
            <input type="number" step="0.01" id="unit_price" name="unit_price" value="{{ old('unit_price', $expense->unit_price ?? '') }}" min="0" required class="admin-input" data-expense-price placeholder="25">
            <p class="admin-field__error" data-field-error="unit_price" hidden></p>
        </div>
    </div>

    <div class="admin-field">
        <label class="admin-label" for="purchased_at">Purchase date</label>
        <input type="date" id="purchased_at" name="purchased_at" value="{{ old('purchased_at', isset($expense) ? $expense->purchased_at->format('Y-m-d') : now()->toDateString()) }}" required class="admin-input">
        <p class="admin-field__error" data-field-error="purchased_at" hidden></p>
    </div>

    <div class="admin-field">
        <span class="admin-label">Purchased by</span>
        <div class="admin-segmented" role="radiogroup" aria-label="Purchased by">
            @foreach(\App\Models\Expense::PURCHASED_BY as $key => $label)
                <label class="admin-segmented__option admin-segmented__option--{{ $key }}">
                    <input
                        type="radio"
                        name="purchased_by"
                        value="{{ $key }}"
                        @checked($selectedPurchaser === $key)
                        required
                    >
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <p class="admin-field__error" data-field-error="purchased_by" hidden></p>
    </div>

    <div class="admin-field">
        <label class="admin-label" for="notes">Notes <span class="admin-label__optional">(optional)</span></label>
        <textarea id="notes" name="notes" rows="2" class="admin-textarea admin-textarea--compact" placeholder="e.g. GoDaddy domain for 1 year">{{ old('notes', $expense->notes ?? '') }}</textarea>
        <p class="admin-field__error" data-field-error="notes" hidden></p>
    </div>

    <div class="admin-expense-form__footer">
        <div class="admin-expense-total-preview" data-expense-total-preview>
            <span class="admin-expense-total-preview__label">Estimated total</span>
            <strong class="admin-expense-total-preview__value" data-expense-total-value>₹0</strong>
        </div>
        <button type="submit" class="admin-btn admin-btn--block" data-expense-submit>
            <span class="admin-btn__loader" aria-hidden="true"></span>
            <span class="admin-btn__text">{{ isset($expense) ? 'Save Expense' : 'Log Expense' }}</span>
        </button>
    </div>
</div>
