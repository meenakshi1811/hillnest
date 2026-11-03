<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailTemplate extends Model
{
    /** Placeholders admins can use in the subject, preheader, heading, and message. */
    public const PLACEHOLDERS = [
        '{first_name}' => "Customer's first name",
        '{name}' => "Customer's full name",
        '{coupon_code}' => 'Coupon code, e.g. DIWALI15',
        '{discount}' => 'Coupon discount, e.g. 15% or ₹100',
    ];

    protected $fillable = [
        'name',
        'subject',
        'preheader',
        'heading',
        'body',
        'coupon_id',
        'button_label',
        'button_url',
    ];

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(EmailCampaign::class);
    }

    /** @return array<string, mixed> */
    public function toContent(): array
    {
        return $this->only(['subject', 'preheader', 'heading', 'body', 'coupon_id', 'button_label', 'button_url']);
    }
}
