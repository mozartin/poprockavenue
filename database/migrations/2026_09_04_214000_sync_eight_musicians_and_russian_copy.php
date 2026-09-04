<?php

use App\Models\BandMember;
use App\Models\EventType;
use App\Models\LiveEvent;
use App\Models\MediaMoment;
use App\Models\RepertoireCategory;
use App\Models\RepertoireSong;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Support\SiteCopy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Refresh website copy from lang (RU + 8-piece), but never overwrite hero —
        // production hero was already customized.
        SiteCopy::seedFromLang(exceptSections: ['hero']);

        SiteSetting::query()
            ->where(function ($query): void {
                $query->where('type', 'translatable')
                    ->orWhere('group', 'copy')
                    ->orWhere('group', 'content');
            })
            ->get()
            ->each(function (SiteSetting $setting): void {
                if (Str::startsWith($setting->key, 'copy.hero.')) {
                    return;
                }

                $decoded = json_decode((string) $setting->value, true);

                if (! is_array($decoded)) {
                    return;
                }

                foreach ($decoded as $locale => $text) {
                    if (is_string($text)) {
                        $decoded[$locale] = $this->replaceMusicianCount($text, (string) $locale);
                    }
                }

                if ($setting->key === 'copy.stats_values.musicians') {
                    foreach (['en', 'nl', 'uk', 'ru'] as $locale) {
                        $decoded[$locale] = '8';
                    }
                }

                $decoded = $this->ensureRussian($decoded, $setting->key);

                $setting->forceFill([
                    'value' => json_encode($decoded, JSON_UNESCAPED_UNICODE),
                ])->save();
            });

        foreach ([
            EventType::class,
            BandMember::class,
            Testimonial::class,
            LiveEvent::class,
            MediaMoment::class,
            RepertoireCategory::class,
            RepertoireSong::class,
        ] as $modelClass) {
            if (! class_exists($modelClass)) {
                continue;
            }

            $modelClass::query()->each(function ($model): void {
                $changed = false;

                foreach ($model->translatable ?? [] as $attribute) {
                    $translations = $model->getTranslations($attribute);

                    if ($translations === []) {
                        continue;
                    }

                    foreach ($translations as $locale => $text) {
                        if (! is_string($text)) {
                            continue;
                        }

                        $updated = $this->replaceMusicianCount($text, (string) $locale);

                        if ($updated !== $text) {
                            $translations[$locale] = $updated;
                            $changed = true;
                        }
                    }

                    $before = $translations;
                    $translations = $this->ensureRussian($translations);

                    if ($translations !== $before) {
                        $changed = true;
                    }

                    if ($changed) {
                        $model->setTranslations($attribute, $translations);
                    }
                }

                if ($changed) {
                    $model->save();
                }
            });
        }

        Cache::forget('site_settings.all');
    }

    public function down(): void
    {
        // Irreversible content sync.
    }

    /**
     * @param  array<string, mixed>  $translations
     * @return array<string, mixed>
     */
    protected function ensureRussian(array $translations, ?string $settingKey = null): array
    {
        $ru = $translations['ru'] ?? null;

        if (is_string($ru) && trim($ru) !== '') {
            return $translations;
        }

        if (is_string($settingKey) && Str::startsWith($settingKey, 'copy.')) {
            $dotted = Str::after($settingKey, 'copy.');
            $fromLang = (string) trans('site.'.$dotted, [], 'ru');

            if ($fromLang !== '' && $fromLang !== 'site.'.$dotted) {
                $translations['ru'] = $this->replaceMusicianCount($fromLang, 'ru');

                return $translations;
            }
        }

        $fallback = $translations['uk'] ?? $translations['en'] ?? null;

        if (is_string($fallback) && trim($fallback) !== '') {
            $translations['ru'] = $this->replaceMusicianCount($fallback, 'ru');
        }

        return $translations;
    }

    protected function replaceMusicianCount(string $text, string $locale): string
    {
        $map = match ($locale) {
            'nl' => [
                '7-koppig' => '8-koppig',
                'Zeven muzikanten' => 'Acht muzikanten',
                'zeven muzikanten' => 'acht muzikanten',
                'Zeven ervaren' => 'Acht ervaren',
            ],
            'uk' => [
                '7-учасний' => '8-учасний',
                'Сім музикантів' => 'Вісім музикантів',
                'сім музикантів' => 'вісім музикантів',
                'Сім досвідчених' => 'Вісім досвідчених',
            ],
            'ru' => [
                '7-участный' => '8-участный',
                '7-участном' => '8-участном',
                'Семь музыкантов' => 'Восемь музыкантов',
                'семь музыкантов' => 'восемь музыкантов',
            ],
            default => [
                '7-Piece' => '8-Piece',
                '7-piece' => '8-piece',
                'Seven musicians' => 'Eight musicians',
                'seven musicians' => 'eight musicians',
                'Seven seasoned' => 'Eight seasoned',
            ],
        };

        return str_replace(array_keys($map), array_values($map), $text);
    }
};
