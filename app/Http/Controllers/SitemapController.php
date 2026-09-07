<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $locales = array_keys(config('app.supported_locales', ['en' => 'EN']));
        $defaultLocale = config('app.fallback_locale', 'en');
        $base = rtrim((string) config('app.url'), '/');

        // Path-based (not route names) so a stale route cache cannot 500 the sitemap.
        $pages = [
            ['path' => '', 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['path' => '/about-us', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/media', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['path' => '/repertoire', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/contact', 'changefreq' => 'monthly', 'priority' => '0.9'],
        ];

        $urls = [];

        foreach ($pages as $page) {
            $alternates = [];

            foreach ($locales as $locale) {
                $alternates[$locale] = $base.'/'.$locale.$page['path'];
            }

            $alternates['x-default'] = $alternates[$defaultLocale] ?? reset($alternates);

            foreach ($locales as $locale) {
                $urls[] = [
                    'loc' => $alternates[$locale],
                    'changefreq' => $page['changefreq'],
                    'priority' => $page['priority'],
                    'alternates' => $alternates,
                ];
            }
        }

        return response()
            ->view('sitemap', [
                'urls' => $urls,
                'lastmod' => now()->toAtomString(),
            ])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
