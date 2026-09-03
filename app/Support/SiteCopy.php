<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SiteCopy
{
    public static function settingKey(string $section, string $field): string
    {
        return "copy.{$section}.{$field}";
    }

    public static function get(string $dottedKey, array $replace = [], ?string $default = null): string
    {
        [$section, $field] = self::splitKey($dottedKey);

        $fromDb = SiteSetting::get(self::settingKey($section, $field));

        $text = is_string($fromDb) && $fromDb !== ''
            ? $fromDb
            : ($default ?? (string) __('site.'.$dottedKey));

        if ($text === 'site.'.$dottedKey && $default !== null) {
            $text = $default;
        }

        foreach ($replace as $search => $value) {
            $text = str_replace(':'.$search, (string) $value, $text);
        }

        return $text;
    }

    public static function section(string $section): array
    {
        $fields = config("site_copy.sections.{$section}.fields", []);
        $result = [];

        foreach (array_keys($fields) as $field) {
            $result[$field] = self::get("{$section}.{$field}");
        }

        return $result;
    }

    public static function sections(): array
    {
        return config('site_copy.sections', []);
    }

    public static function seedFromLang(): void
    {
        $locales = array_keys(config('app.supported_locales', ['en' => 'EN']));

        foreach (self::sections() as $section => $config) {
            foreach (array_keys($config['fields'] ?? []) as $field) {
                $translations = [];

                foreach ($locales as $locale) {
                    if ($section === 'stats_values') {
                        $translations[$locale] = config("site_copy.stats_value_defaults.{$field}", '');
                        continue;
                    }

                    $translations[$locale] = (string) trans("site.{$section}.{$field}", [], $locale);
                }

                SiteSetting::set(
                    self::settingKey($section, $field),
                    $translations,
                    'translatable',
                    'copy'
                );
            }
        }

        Cache::forget('site_settings.all');
    }

    /**
     * @return array{0: string, 1: string}
     */
    protected static function splitKey(string $dottedKey): array
    {
        $parts = explode('.', $dottedKey, 2);

        if (count($parts) !== 2) {
            return [$dottedKey, ''];
        }

        return [$parts[0], $parts[1]];
    }
}
