<?php

namespace App\Support;

use App\Models\MenuSection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

class SiteMenus
{
    /**
     * @return Collection<int, MenuSection>
     */
    public static function sections(string $location): Collection
    {
        if (! self::tablesReady()) {
            return collect();
        }

        $locale = app()->getLocale();

        try {
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
        } catch (Throwable $e) {
            Log::warning('SiteMenus query failed', [
                'location' => $location,
                'message' => $e->getMessage(),
            ]);

            return collect();
        }
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

    public static function tablesReady(): bool
    {
        try {
            return Schema::hasTable('menu_sections') && Schema::hasTable('menu_items');
        } catch (Throwable) {
            return false;
        }
    }
}
