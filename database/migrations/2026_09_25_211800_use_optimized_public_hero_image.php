<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        $updated = DB::table('site_settings')
            ->where('key', 'hero_image')
            ->update([
                'value' => '/images/hero.jpg',
                'updated_at' => now(),
            ]);

        if ($updated === 0) {
            DB::table('site_settings')->insert([
                'key' => 'hero_image',
                'value' => '/images/hero.jpg',
                'type' => 'text',
                'group' => 'media',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Cache::forget('site_settings.all');
        Cache::forget('site_setting.hero_image');
    }

    public function down(): void
    {
        // Irreversible media path reset.
    }
};
