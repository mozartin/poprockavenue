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

        // Black logo stack reads as a void in WhatsApp / messenger previews.
        SiteSetting::set('og_image', '/images/logo/og-share-v1.jpg', 'text', 'media');

        Cache::forget('site_settings.all');
        Cache::forget('site_setting.og_image');
    }

    public function down(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        SiteSetting::set('og_image', '/images/logo/logo-stack-v1.png', 'text', 'media');

        Cache::forget('site_settings.all');
        Cache::forget('site_setting.og_image');
    }
};
