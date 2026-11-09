<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiteVisit extends Model
{
    protected $fillable = [
        'visitor_id',
        'user_id',
        'ip_address',
        'device',
        'browser',
        'source',
        'landing_path',
        'referrer',
        'page_views',
        'cart_adds',
        'order_id',
        'last_seen_at',
    ];

    protected function casts(): array
    {
        return [
            'last_seen_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function cartEvents(): HasMany
    {
        return $this->hasMany(CartEvent::class);
    }

    public function durationSeconds(): int
    {
        return $this->last_seen_at ? max(0, $this->created_at->diffInSeconds($this->last_seen_at, true)) : 0;
    }

    public static function formatDuration(int|float $seconds): string
    {
        $seconds = (int) round($seconds);

        if ($seconds < 60) {
            return $seconds.'s';
        }

        $minutes = intdiv($seconds, 60);

        return $minutes < 60
            ? $minutes.'m '.($seconds % 60).'s'
            : intdiv($minutes, 60).'h '.($minutes % 60).'m';
    }
}
