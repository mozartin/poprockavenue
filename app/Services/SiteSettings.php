<?php

namespace App\Services;

use App\Models\SiteSetting;
use App\Support\MediaPath;

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
        $contacts = self::phoneContacts();

        if ($contacts !== []) {
            return $contacts[0]['phone'] ?: $contacts[0]['label'];
        }

        return (string) self::get('phone', '+31 6 47 773 616');
    }

    public static function phoneLink(): string
    {
        $contacts = self::phoneContacts();

        if ($contacts !== [] && filled($contacts[0]['href'])) {
            return $contacts[0]['href'];
        }

        return 'tel:'.preg_replace('/[^\d+]/', '', self::phone());
    }

    /**
     * Contact phone lines from the multiline `contact_phones` setting.
     * One line per contact, e.g. "+31 6 86 07 84 11 Max – 🇬🇧 English".
     *
     * @return list<array{label: string, phone: ?string, href: ?string}>
     */
    public static function phoneContacts(): array
    {
        $raw = self::get('contact_phones');

        if (! filled($raw)) {
            $fallback = (string) self::get('phone', '');

            if ($fallback === '') {
                return [];
            }

            $raw = $fallback;
        }

        return collect(preg_split("/\r\n|\n|\r/", (string) $raw) ?: [])
            ->map(fn (string $line): string => trim($line))
            ->filter()
            ->map(function (string $line): array {
                $phone = null;

                if (preg_match('/\+?\d[\d\s\-()]{6,}\d/', $line, $matches)) {
                    $phone = trim($matches[0]);
                }

                return [
                    'label' => $line,
                    'phone' => $phone,
                    'href' => $phone
                        ? 'tel:'.preg_replace('/[^\d+]/', '', $phone)
                        : null,
                ];
            })
            ->values()
            ->all();
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

    /**
     * Extract a YouTube video ID from the showreel URL (watch / youtu.be / embed / shorts).
     */
    public static function showreelYoutubeId(): ?string
    {
        $url = self::showreelUrl();

        if (! filled($url)) {
            return null;
        }

        if (preg_match(
            '~(?:youtube\.com/(?:watch\?v=|embed/|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})~',
            $url,
            $matches,
        )) {
            return $matches[1];
        }

        return null;
    }

    public static function imageUrl(?string $path, string $default): string
    {
        return MediaPath::url($path, $default);
    }

    public static function heroImage(): string
    {
        return self::imageUrl(self::get('hero_image'), 'images/hero.jpg');
    }

    /**
     * Image used for Open Graph / Twitter / messengers when sharing a link.
     * Prefer a square logo so messengers show a compact left thumbnail (not a huge photo).
     */
    public static function ogImage(): string
    {
        // Brighter slate share card (black logo stack reads as a void in WhatsApp).
        $default = 'images/logo/og-share-v1.jpg';
        $og = self::get('og_image');

        if (filled($og)) {
            return self::imageUrl($og, $default);
        }

        return self::imageUrl(null, $default);
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
                'value' => site_t('stats_values.musicians', [], 'Full'),
                'label' => site_t('stats.musicians'),
            ],
            'events' => [
                'value' => site_t('stats_values.events', [], '500+'),
                'label' => site_t('stats.events'),
            ],
            'experience' => [
                'value' => site_t('stats_values.experience', [], '200+'),
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
