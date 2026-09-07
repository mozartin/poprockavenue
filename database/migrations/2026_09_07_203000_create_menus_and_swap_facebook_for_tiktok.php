<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_sections', function (Blueprint $table) {
            $table->id();
            $table->string('location'); // header | footer
            $table->json('title')->nullable();
            $table->boolean('show_title')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_section_id')->constrained()->cascadeOnDelete();
            $table->json('label');
            $table->string('link_type')->default('route'); // route | url
            $table->string('link_value'); // route name or full URL / path
            $table->string('anchor')->nullable();
            $table->boolean('open_in_new_tab')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Replace Facebook with TikTok in social settings.
        DB::table('site_settings')
            ->where('key', 'facebook_url')
            ->update(['key' => 'tiktok_url', 'value' => 'https://www.tiktok.com/@poprockavenue']);

        if (! DB::table('site_settings')->where('key', 'tiktok_url')->exists()) {
            DB::table('site_settings')->insert([
                'key' => 'tiktok_url',
                'value' => 'https://www.tiktok.com/@poprockavenue',
                'type' => 'text',
                'group' => 'social',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->seedDefaultMenus();
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menu_sections');
    }

    protected function seedDefaultMenus(): void
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

        $headerItems = [
            [0, $t('About Us', 'Over ons', 'Про нас', 'О нас'), 'route', 'about', null],
            [1, $t('Events', 'Events', 'Афіша', 'Афиша'), 'route', 'home', '#events'],
            [2, $t('Live Moments', 'Live Moments', 'Live Moments', 'Live Moments'), 'route', 'media', null],
            [3, $t('Contact', 'Contact', 'Контакт', 'Контакт'), 'route', 'contact', null],
        ];

        foreach ($headerItems as [$sort, $label, $type, $value, $anchor]) {
            DB::table('menu_items')->insert([
                'menu_section_id' => $headerId,
                'label' => $label,
                'link_type' => $type,
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

        $navigateItems = [
            [0, $t('About Us', 'Over ons', 'Про нас', 'О нас'), 'about', null],
            [1, $t('Events', 'Events', 'Афіша', 'Афиша'), 'home', '#events'],
            [2, $t('Live Moments', 'Live Moments', 'Live Moments', 'Live Moments'), 'media', null],
            [3, $t('Repertoire', 'Repertoire', 'Репертуар', 'Репертуар'), 'repertoire', null],
        ];

        foreach ($navigateItems as [$sort, $label, $value, $anchor]) {
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

        $serviceItems = [
            [0, $t('Weddings', 'Bruiloften', 'Весілля', 'Свадьбы')],
            [1, $t('Corporate', 'Zakelijk', 'Корпоративи', 'Корпоративы')],
            [2, $t('Private parties', 'Privéfeesten', 'Приватні вечірки', 'Частные вечеринки')],
            [3, $t('Christmas & NY', 'Kerst & NY', 'Різдво & НР', 'Рождество & НГ')],
        ];

        foreach ($serviceItems as [$sort, $label]) {
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
};
