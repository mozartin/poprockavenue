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
            ['route' => 'about', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['route' => 'media', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['route' => 'repertoire', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['route' => 'contact', 'changefreq' => 'monthly', 'priority' => '0.9'],
        ];

        $urls = [];

        foreach ($pages as $page) {
            $urls = [...$urls, ...$this->urlEntries(
                collect($locales)->mapWithKeys(fn (string $locale) => [
                    $locale => localized_route($page['route'], [], $locale),
                ])->all(),
                $locales,
                $defaultLocale,
                $page['changefreq'],
                $page['priority'],
            )];
        }

        return response()
            ->view('sitemap', [
                'urls' => $urls,
                'lastmod' => now()->toAtomString(),
            ])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * @param  array<string, string>  $alternatesByLocale
     * @param  list<string>  $locales
     * @return list<array{loc: string, changefreq: string, priority: string, alternates: array<string, string>}>
     */
    private function urlEntries(
        array $alternatesByLocale,
        array $locales,
        string $defaultLocale,
        string $changefreq,
        string $priority,
    ): array {
        $alternates = $alternatesByLocale;
        $alternates['x-default'] = $alternates[$defaultLocale] ?? reset($alternates);

        $entries = [];

        foreach ($locales as $locale) {
            $entries[] = [
                'loc' => $alternates[$locale],
                'changefreq' => $changefreq,
                'priority' => $priority,
                'alternates' => $alternates,
            ];
        }

        return $entries;
    }
}
