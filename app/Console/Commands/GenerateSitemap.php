<?php

namespace App\Console\Commands;

use App\Models\EventType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate public/sitemap.xml for search engines';

    public function handle(): int
    {
        $locales = array_keys(config('app.supported_locales', ['en' => 'EN']));
        $defaultLocale = config('app.fallback_locale', 'en');
        $base = rtrim((string) config('app.url'), '/');

        $pages = [
            ['path' => '', 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['path' => '/the-band', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/media', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['path' => '/repertoire', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/contact', 'changefreq' => 'monthly', 'priority' => '0.9'],
        ];

        foreach (EventType::query()->where('is_active', true)->orderBy('sort_order')->get(['slug']) as $event) {
            $pages[] = [
                'path' => '/'.$event->slug,
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ];
        }

        $lastmod = now()->toAtomString();
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'."\n";

        foreach ($pages as $page) {
            $alternates = [];

            foreach ($locales as $locale) {
                $alternates[$locale] = $base.'/'.$locale.$page['path'];
            }

            $alternates['x-default'] = $alternates[$defaultLocale] ?? reset($alternates);

            foreach ($locales as $locale) {
                $loc = $alternates[$locale];
                $xml .= "  <url>\n";
                $xml .= '    <loc>'.e($loc)."</loc>\n";
                $xml .= '    <lastmod>'.$lastmod."</lastmod>\n";
                $xml .= '    <changefreq>'.$page['changefreq']."</changefreq>\n";
                $xml .= '    <priority>'.$page['priority']."</priority>\n";

                foreach ($alternates as $hreflang => $href) {
                    $xml .= '    <xhtml:link rel="alternate" hreflang="'.e($hreflang).'" href="'.e($href).'" />'."\n";
                }

                $xml .= "  </url>\n";
            }
        }

        $xml .= '</urlset>'."\n";

        File::put(public_path('sitemap.xml'), $xml);

        $this->info('Sitemap written to public/sitemap.xml ('.count($pages).' page types)');

        return self::SUCCESS;
    }
}
