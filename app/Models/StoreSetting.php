<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /** @var array<string, string|null> */
    protected static array $cache = [];

    public static function get(string $key, mixed $default = null): mixed
    {
        if (! array_key_exists($key, static::$cache)) {
            static::$cache[$key] = static::query()->where('key', $key)->value('value');
        }

        return static::$cache[$key] ?? $default;
    }

    public static function getBool(string $key, bool $default = false): bool
    {
        $value = static::get($key, $default ? '1' : '0');

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public static function getFloat(string $key, float $default = 0): float
    {
        return (float) static::get($key, (string) $default);
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value],
        );

        static::$cache[$key] = (string) $value;
    }

    public static function clearCache(): void
    {
        static::$cache = [];
    }
}
