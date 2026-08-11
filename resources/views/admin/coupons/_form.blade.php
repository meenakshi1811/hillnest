@csrf

@php
    $selectedType = old('type', $coupon->type ?? 'fixed');
    $selectedUser = old('user_id', $coupon->user_id ?? '');
    $defaultCode = old('code', $coupon->code ?? \App\Models\Coupon::generateCode());
@endphp

<div class="admin-form-grid admin-expense-form">
    <div class="admin-field">
        <label class="admin-label" for="user_id">Assign to customer</label>
        <select id="user_id" name="user_id" required class="admin-select">
            <option value="" disabled @selected(! $selectedUser)>Select a customer</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}" @selected((string) $selectedUser === (string) $customer->id)>
                    {{ $customer->name }} ({{ $customer->loginIdentifier() }})
                </option>
            @endforeach
        </select>
        <p class="admin-field__error" data-field-error="user_id" hidden></p>
    </div>

    <div class="admin-field">
        <label class="admin-label" for="code">Coupon code</label>
        <div class="admin-input-group">
            <input type="text" id="code" name="code" value="{{ $defaultCode }}" required class="admin-input" style="text-transform:uppercase" maxlength="30" placeholder="HN-ABC123">
            @unless(isset($coupon))
            <button type="button" class="admin-btn admin-btn--sm" data-generate-code>Generate</button>
            @endunless
        </div>
        <p class="admin-field__error" data-field-error="code" hidden></p>
    </div>

    <div class="admin-field">
        <span class="admin-label">Discount type</span>
        <div class="admin-segmented" role="radiogroup" aria-label="Discount type">
            @foreach(\App\Models\Coupon::TYPES as $key => $label)
                <label class="admin-segmented__option admin-segmented__option--{{ $key }}">
                    <input
                        type="radio"
                        name="type"
                        value="{{ $key }}"
                        @checked($selectedType === $key)
                        required
                        data-coupon-type
                    >
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <p class="admin-field__error" data-field-error="type" hidden></p>
    </div>

    <div class="admin-field">
        <label class="admin-label" for="value" data-coupon-value-label>
            {{ $selectedType === 'percent' ? 'Discount percentage' : 'Discount amount (₹)' }}
        </label>
        <input
            type="number"
            step="0.01"
            id="value"
            name="value"
            value="{{ old('value', $coupon->value ?? '') }}"
            min="0.01"
            @if($selectedType === 'percent') max="100" @endif
            required
            class="admin-input"
            data-coupon-value
            placeholder="{{ $selectedType === 'percent' ? '10' : '200' }}"
        >
        <p class="admin-field__error" data-field-error="value" hidden></p>
    </div>

    <div class="admin-field">
        <label class="admin-label" for="expires_at">Expiry date <span class="admin-label__optional">(optional)</span></label>
        <input type="date" id="expires_at" name="expires_at" value="{{ old('expires_at', isset($coupon) && $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '') }}" class="admin-input" min="{{ now()->toDateString() }}">
        <p class="admin-field__error" data-field-error="expires_at" hidden></p>
    </div>

    <div class="admin-field">
        <label class="admin-label" for="notes">Notes <span class="admin-label__optional">(optional)</span></label>
        <textarea id="notes" name="notes" rows="2" class="admin-textarea admin-textarea--compact" placeholder="Internal note for this coupon">{{ old('notes', $coupon->notes ?? '') }}</textarea>
        <p class="admin-field__error" data-field-error="notes" hidden></p>
    </div>

    <div class="admin-expense-form__footer">
        <button type="submit" class="admin-btn admin-btn--block" data-coupon-submit>
            <span class="admin-btn__loader" aria-hidden="true"></span>
            <span class="admin-btn__text">{{ isset($coupon) ? 'Save Coupon' : 'Assign Coupon' }}</span>
        </button>
    </div>
</div>
