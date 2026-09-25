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

        SiteSetting::set('hero_image', '/images/hero.jpg', 'text', 'media');
        SiteSetting::set('live_video_image', '/images/live-video.jpg', 'text', 'media');
        SiteSetting::set('cta_background_image', '/images/cta-bg.jpg', 'text', 'media');

        Cache::forget('site_settings.all');
        Cache::forget('site_setting.hero_image');
        Cache::forget('site_setting.live_video_image');
        Cache::forget('site_setting.cta_background_image');
    }

    public function down(): void
    {
        // Irreversible media path reset.
    }
};
