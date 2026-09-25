<?php

use App\Models\SiteSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        // Use the model setter so per-key Filament/SiteSetting caches clear.
        SiteSetting::set('hero_image', '/images/hero.jpg', 'text', 'media');

        Cache::forget('site_settings.all');
        Cache::forget('site_setting.hero_image');
    }

    public function down(): void
    {
        // Irreversible media path reset.
    }
};
