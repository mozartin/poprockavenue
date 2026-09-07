<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Safety net if the previous menu migration failed mid-way on production
 * (e.g. unique key conflict when renaming facebook_url → tiktok_url).
 */
return new class extends Migration
{
    public function up(): void
    {
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

        if (Schema::hasTable('site_settings')) {
            $hasFacebook = DB::table('site_settings')->where('key', 'facebook_url')->exists();
            $hasTiktok = DB::table('site_settings')->where('key', 'tiktok_url')->exists();

            if ($hasFacebook && $hasTiktok) {
                DB::table('site_settings')->where('key', 'facebook_url')->delete();
            } elseif ($hasFacebook && ! $hasTiktok) {
                DB::table('site_settings')->where('key', 'facebook_url')->update([
                    'key' => 'tiktok_url',
                    'value' => 'https://www.tiktok.com/@poprockavenue',
                    'updated_at' => now(),
                ]);
            } elseif (! $hasTiktok) {
                DB::table('site_settings')->insert([
                    'key' => 'tiktok_url',
                    'value' => 'https://www.tiktok.com/@poprockavenue',
                    'type' => 'text',
                    'group' => 'social',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        if (Schema::hasTable('menu_sections') && DB::table('menu_sections')->count() === 0) {
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
        }
    }

    public function down(): void
    {
        // Keep tables — created by the primary menus migration.
    }
};
