<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $locales = array_keys(config('app.supported_locales', ['en' => 'EN']));
        $defaultLocale = config('app.fallback_locale', 'en');

        $pages = [
            ['route' => 'home', 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['route' => 'band', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['route' => 'weddings', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['route' => 'corporate', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['route' => 'private-parties', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['route' => 'christmas', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['route' => 'media', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['route' => 'repertoire', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['route' => 'testimonials', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['route' => 'contact', 'changefreq' => 'monthly', 'priority' => '0.9'],
        ];

        $urls = [];

        foreach ($pages as $page) {
            $alternates = [];

            foreach ($locales as $locale) {
                $alternates[$locale] = localized_route($page['route'], [], $locale);
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
