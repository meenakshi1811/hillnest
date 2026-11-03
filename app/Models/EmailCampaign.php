<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailCampaign extends Model
{
    public const AUDIENCES = [
        'all' => 'All customers',
        'selected' => 'Selected customers',
    ];

    protected $fillable = [
        'email_template_id',
        'template_name',
        'subject',
        'content',
        'audience',
        'total_recipients',
        'sent_count',
        'failed_count',
        'created_by',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'completed_at' => 'datetime',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'email_template_id');
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(EmailCampaignRecipient::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pendingCount(): int
    {
        return max(0, $this->total_recipients - $this->sent_count - $this->failed_count);
    }

    public function isComplete(): bool
    {
        return $this->completed_at !== null;
    }

    public function getAudienceLabelAttribute(): string
    {
        return self::AUDIENCES[$this->audience] ?? ucfirst($this->audience);
    }
}
