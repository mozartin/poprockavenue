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
        $raw = self::get('booking_email', config('mail.booking_to', 'booking@poprockavenue.nl'));

        return collect(preg_split('/[,;]+/', (string) $raw))
            ->map(fn (string $email) => trim($email))
            ->filter()
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

    public static function facebook(): ?string
    {
        return self::get('facebook_url');
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
        $image = $path ?: $default;

        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }

        return asset(ltrim($image, '/'));
    }

    public static function heroImage(): string
    {
        return self::imageUrl(self::get('hero_image'), 'images/hero.jpg');
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
        return [
            ['value' => site_t('stats_values.musicians', [], '7'), 'label' => site_t('stats.musicians')],
            ['value' => site_t('stats_values.events', [], '500+'), 'label' => site_t('stats.events')],
            ['value' => site_t('stats_values.experience', [], '15+'), 'label' => site_t('stats.experience')],
            ['value' => site_t('stats_values.guarantee', [], '100%'), 'label' => site_t('stats.guarantee')],
        ];
    }
}
