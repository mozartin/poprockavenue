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

        // Cache the raw DB value only — never the locale-resolved string,
        // otherwise the first visitor's language sticks for everyone.
        $raw = Cache::rememberForever("site_setting.{$key}", fn () => [
            'value' => $setting->value,
            'type' => $setting->type,
        ]);

        return static::castValue($raw['value'], $raw['type']) ?? $default;
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
        $raw = Cache::rememberForever('site_settings.all', function () {
            return static::query()
                ->get()
                ->mapWithKeys(fn (self $setting) => [
                    $setting->key => [
                        'value' => $setting->value,
                        'type' => $setting->type,
                    ],
                ])
                ->all();
        });

        return collect($raw)
            ->mapWithKeys(fn (array $item, string $key) => [
                $key => static::castValue($item['value'], $item['type']),
            ])
            ->all();
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
        $localized = $decoded[$locale] ?? null;

        if (is_string($localized) && $localized !== '') {
            return $localized;
        }

        $fallback = $decoded['en'] ?? null;

        if (is_string($fallback) && $fallback !== '') {
            return $fallback;
        }

        foreach ($decoded as $candidate) {
            if (is_string($candidate) && $candidate !== '') {
                return $candidate;
            }
        }

        return null;
    }

    protected static function booted(): void
    {
        static::saved(function (self $setting): void {
            Cache::forget('site_settings.all');
            Cache::forget("site_setting.{$setting->key}");
        });
        static::deleted(function (self $setting): void {
            Cache::forget('site_settings.all');
            Cache::forget("site_setting.{$setting->key}");
        });
    }
}
