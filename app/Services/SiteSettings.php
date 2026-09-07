<?php

namespace App\Services;

use App\Models\SiteSetting;

class SiteSettings
{
    public static function get(string $key, mixed $default = null): mixed
    {
        return SiteSetting::get($key, $default);
    }

    public static function all(): array
    {
        return SiteSetting::allCached();
    }

    public static function bookingEmails(): array
    {
        // Coolify/env wins when set; otherwise Filament site setting.
        $raw = config('mail.booking_to')
            ?: self::get('booking_email')
            ?: 'booking@poprockavenue.nl';

        return collect(preg_split('/[,;]+/', (string) $raw))
            ->map(fn (string $email) => trim($email))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public static function bookingEmail(): string
    {
        return self::bookingEmails()[0] ?? 'booking@poprockavenue.nl';
    }

    public static function phone(): string
    {
        return (string) self::get('phone', '+31 6 12 345 678');
    }

    public static function phoneLink(): string
    {
        return 'tel:'.preg_replace('/\s+/', '', self::phone());
    }

    public static function email(): string
    {
        return (string) self::get('email', 'booking@poprockavenue.nl');
    }

    public static function instagram(): ?string
    {
        return self::get('instagram_url');
    }

    public static function tiktok(): ?string
    {
        return self::get('tiktok_url');
    }

    public static function youtube(): ?string
    {
        return self::get('youtube_url');
    }

    public static function showreelUrl(): ?string
    {
        return self::get('showreel_url');
    }

    public static function imageUrl(?string $path, string $default): string
    {
        return \App\Support\MediaPath::url($path, $default);
    }

    public static function heroImage(): string
    {
        return self::imageUrl(self::get('hero_image'), 'images/hero.jpg');
    }

    /**
     * Image used for Open Graph / Twitter / messengers when sharing a link.
     */
    public static function ogImage(): string
    {
        $og = self::get('og_image');

        if (filled($og)) {
            return self::imageUrl($og, 'images/hero.jpg');
        }

        return self::heroImage();
    }

    public static function ogSiteName(): string
    {
        $name = self::get('og_site_name');

        return filled($name) ? (string) $name : 'POP/ROCK AVENUE';
    }

    public static function aboutImage(): string
    {
        return self::imageUrl(self::get('about_image'), 'images/about.jpg');
    }

    public static function liveVideoImage(): string
    {
        return self::imageUrl(self::get('live_video_image'), 'images/live-video.jpg');
    }

    public static function ctaBackgroundImage(): string
    {
        return self::imageUrl(self::get('cta_background_image'), 'images/cta-bg.jpg');
    }

    public static function stats(): array
    {
        $items = [
            'musicians' => [
                'value' => site_t('stats_values.musicians', [], '8'),
                'label' => site_t('stats.musicians'),
            ],
            'events' => [
                'value' => site_t('stats_values.events', [], '500+'),
                'label' => site_t('stats.events'),
            ],
            'experience' => [
                'value' => site_t('stats_values.experience', [], '15+'),
                'label' => site_t('stats.experience'),
            ],
            'guarantee' => [
                'value' => site_t('stats_values.guarantee', [], '100%'),
                'label' => site_t('stats.guarantee'),
            ],
        ];

        return collect($items)
            ->filter(fn (array $stat, string $key) => (bool) SiteSetting::get("stats_active.{$key}", true))
            ->values()
            ->all();
    }
}
