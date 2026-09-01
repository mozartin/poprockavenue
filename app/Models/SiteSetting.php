<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::query()->where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        if ($setting->type === 'translatable') {
            return static::resolveTranslatable($setting->value);
        }

        return Cache::rememberForever("site_setting.{$key}", function () use ($setting, $default) {
            return static::castValue($setting->value, $setting->type) ?? $default;
        });
    }

    public static function set(string $key, mixed $value, string $type = 'text', string $group = 'general'): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : (string) $value,
                'type' => $type,
                'group' => $group,
            ]
        );

        Cache::forget("site_setting.{$key}");
        Cache::forget('site_settings.all');
    }

    public static function allCached(): array
    {
        return Cache::rememberForever('site_settings.all', function () {
            return static::query()
                ->get()
                ->mapWithKeys(fn (self $setting) => [
                    $setting->key => static::castValue($setting->value, $setting->type),
                ])
                ->all();
        });
    }

    protected static function castValue(?string $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'json' => json_decode($value ?? '[]', true),
            'translatable' => static::resolveTranslatable($value),
            default => $value,
        };
    }

    protected static function resolveTranslatable(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $decoded = json_decode($value, true);

        if (! is_array($decoded)) {
            return $value;
        }

        $locale = app()->getLocale();

        return $decoded[$locale] ?? $decoded['en'] ?? reset($decoded) ?: null;
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('site_settings.all'));
        static::deleted(fn () => Cache::forget('site_settings.all'));
    }
}
