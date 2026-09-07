<?php

namespace App\Support;

use App\Models\MenuSection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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

        if ($attempted) {
            return;
        }

        $attempted = true;

        try {
            if (! Schema::hasTable('menu_sections')) {
                Schema::create('menu_sections', function (Blueprint $table) {
                    $table->id();
                    $table->string('location');
                    $table->json('title')->nullable();
                    $table->boolean('show_title')->default(true);
                    $table->unsignedInteger('sort_order')->default(0);
                    $table->boolean('is_active')->default(true);
                    $table->timestamps();
                });
            }

            if (! Schema::hasTable('menu_items')) {
                Schema::create('menu_items', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('menu_section_id')->constrained()->cascadeOnDelete();
                    $table->json('label');
                    $table->string('link_type')->default('route');
                    $table->string('link_value');
                    $table->string('anchor')->nullable();
                    $table->boolean('open_in_new_tab')->default(false);
                    $table->unsignedInteger('sort_order')->default(0);
                    $table->boolean('is_active')->default(true);
                    $table->timestamps();
                });
            }

            if (Schema::hasTable('menu_sections') && DB::table('menu_sections')->count() === 0) {
                self::seedDefaults();
            }

            self::ensureLiveMomentsInMenus();
        } catch (Throwable $e) {
            Log::warning('SiteMenus ensureReady failed', ['message' => $e->getMessage()]);
        }
    }

    /**
     * Keep Live Moments in header/footer navigate menus if an older seed omitted it.
     */
    protected static function ensureLiveMomentsInMenus(): void
    {
        if (! self::tablesReady()) {
            return;
        }

        $t = fn (string $en, string $nl, string $uk, string $ru) => json_encode([
            'en' => $en,
            'nl' => $nl,
            'uk' => $uk,
            'ru' => $ru,
        ], JSON_UNESCAPED_UNICODE);

        $label = $t('Live Moments', 'Live Moments', 'Live Moments', 'Live Moments');
        $added = false;

        $headerSectionIds = DB::table('menu_sections')
            ->where('location', 'header')
            ->where('is_active', true)
            ->pluck('id');

        foreach ($headerSectionIds as $sectionId) {
            $exists = DB::table('menu_items')
                ->where('menu_section_id', $sectionId)
                ->where('link_type', 'route')
                ->where('link_value', 'media')
                ->exists();

            if ($exists) {
                continue;
            }

            $contact = DB::table('menu_items')
                ->where('menu_section_id', $sectionId)
                ->where('link_value', 'contact')
                ->first();

            $maxSort = (int) DB::table('menu_items')->where('menu_section_id', $sectionId)->max('sort_order');
            $sortOrder = $contact ? max(0, ((int) $contact->sort_order) - 1) : ($maxSort + 1);

            DB::table('menu_items')->insert([
                'menu_section_id' => $sectionId,
                'label' => $label,
                'link_type' => 'route',
                'link_value' => 'media',
                'anchor' => null,
                'open_in_new_tab' => false,
                'sort_order' => $sortOrder,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $added = true;
        }

        if ($added) {
            self::forgetCache();
        }
    }

    protected static function seedDefaults(): void
    {
        $t = fn (string $en, string $nl, string $uk, string $ru) => json_encode([
            'en' => $en,
            'nl' => $nl,
            'uk' => $uk,
            'ru' => $ru,
        ], JSON_UNESCAPED_UNICODE);

        $headerId = DB::table('menu_sections')->insertGetId([
            'location' => 'header',
            'title' => $t('Main menu', 'Hoofdmenu', 'Головне меню', 'Главное меню'),
            'show_title' => false,
            'sort_order' => 0,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ([
            [0, $t('About Us', 'Over ons', 'Про нас', 'О нас'), 'about', null],
            [1, $t('Events', 'Events', 'Афіша', 'Афиша'), 'home', '#events'],
            [2, $t('Live Moments', 'Live Moments', 'Live Moments', 'Live Moments'), 'media', null],
            [3, $t('Contact', 'Contact', 'Контакт', 'Контакт'), 'contact', null],
        ] as [$sort, $label, $value, $anchor]) {
            DB::table('menu_items')->insert([
                'menu_section_id' => $headerId,
                'label' => $label,
                'link_type' => 'route',
                'link_value' => $value,
                'anchor' => $anchor,
                'open_in_new_tab' => false,
                'sort_order' => $sort,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $navigateId = DB::table('menu_sections')->insertGetId([
            'location' => 'footer',
            'title' => $t('Navigate', 'Navigatie', 'Навігація', 'Навигация'),
            'show_title' => true,
            'sort_order' => 0,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ([
            [0, $t('About Us', 'Over ons', 'Про нас', 'О нас'), 'about', null],
            [1, $t('Events', 'Events', 'Афіша', 'Афиша'), 'home', '#events'],
            [2, $t('Live Moments', 'Live Moments', 'Live Moments', 'Live Moments'), 'media', null],
            [3, $t('Repertoire', 'Repertoire', 'Репертуар', 'Репертуар'), 'repertoire', null],
        ] as [$sort, $label, $value, $anchor]) {
            DB::table('menu_items')->insert([
                'menu_section_id' => $navigateId,
                'label' => $label,
                'link_type' => 'route',
                'link_value' => $value,
                'anchor' => $anchor,
                'open_in_new_tab' => false,
                'sort_order' => $sort,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $servicesId = DB::table('menu_sections')->insertGetId([
            'location' => 'footer',
            'title' => $t('Services', 'Diensten', 'Послуги', 'Услуги'),
            'show_title' => true,
            'sort_order' => 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ([
            [0, $t('Weddings', 'Bruiloften', 'Весілля', 'Свадьбы')],
            [1, $t('Corporate', 'Zakelijk', 'Корпоративи', 'Корпоративы')],
            [2, $t('Private parties', 'Privéfeesten', 'Приватні вечірки', 'Частные вечеринки')],
            [3, $t('Christmas & NY', 'Kerst & NY', 'Різдво & НР', 'Рождество & НГ')],
        ] as [$sort, $label]) {
            DB::table('menu_items')->insert([
                'menu_section_id' => $servicesId,
                'label' => $label,
                'link_type' => 'route',
                'link_value' => 'home',
                'anchor' => '#services',
                'open_in_new_tab' => false,
                'sort_order' => $sort,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
