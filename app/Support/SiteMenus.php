<?php

namespace App\Support;

use App\Models\MenuSection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SiteMenus
{
    /**
     * @return Collection<int, MenuSection>
     */
    public static function sections(string $location): Collection
    {
        $locale = app()->getLocale();

        return Cache::remember(
            "site_menus.{$location}.{$locale}",
            now()->addMinutes(10),
            fn () => MenuSection::query()
                ->location($location)
                ->active()
                ->ordered()
                ->with(['items' => fn ($q) => $q->active()->ordered()])
                ->get()
        );
    }

    /**
     * Flat header links from all active header sections.
     *
     * @return Collection<int, \App\Models\MenuItem>
     */
    public static function headerItems(): Collection
    {
        return self::sections('header')->flatMap(fn (MenuSection $section) => $section->items);
    }

    public static function forgetCache(): void
    {
        foreach (array_keys(config('app.supported_locales', ['en' => 'EN'])) as $locale) {
            Cache::forget("site_menus.header.{$locale}");
            Cache::forget("site_menus.footer.{$locale}");
        }
    }
}
