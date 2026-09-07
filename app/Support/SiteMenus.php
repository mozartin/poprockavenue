<?php

namespace App\Support;

use App\Models\MenuSection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
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
        self::ensureReady();

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

    /**
     * Create menu tables (and seed defaults) if a deploy skipped migrate.
     */
    public static function ensureReady(): void
    {
        static $attempted = false;

        if ($attempted || self::tablesReady()) {
            return;
        }

        $attempted = true;

        try {
            foreach ([
                'database/migrations/2026_09_07_203000_create_menus_and_swap_facebook_for_tiktok.php',
                'database/migrations/2026_09_07_204500_ensure_menu_tables_exist.php',
            ] as $path) {
                if (! is_file(base_path($path))) {
                    continue;
                }

                Artisan::call('migrate', [
                    '--force' => true,
                    '--no-interaction' => true,
                    '--path' => $path,
                ]);
            }
        } catch (Throwable $e) {
            Log::warning('SiteMenus ensureReady failed', ['message' => $e->getMessage()]);
        }
    }
}
