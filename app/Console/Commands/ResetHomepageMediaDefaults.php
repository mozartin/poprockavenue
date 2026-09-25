<?php

namespace App\Console\Commands;

use App\Models\SiteSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class ResetHomepageMediaDefaults extends Command
{
    protected $signature = 'site:reset-homepage-media';

    protected $description = 'Point homepage hero / live / CTA images at optimized public JPEGs and clear setting caches';

    public function handle(): int
    {
        if (! Schema::hasTable('site_settings')) {
            $this->warn('site_settings table missing');

            return self::SUCCESS;
        }

        SiteSetting::set('hero_image', '/images/hero.jpg', 'text', 'media');
        SiteSetting::set('live_video_image', '/images/live-video.jpg', 'text', 'media');
        SiteSetting::set('cta_background_image', '/images/cta-bg.jpg', 'text', 'media');

        Cache::forget('site_settings.all');
        Cache::forget('site_setting.hero_image');
        Cache::forget('site_setting.live_video_image');
        Cache::forget('site_setting.cta_background_image');

        $this->info('Homepage media defaults restored.');

        return self::SUCCESS;
    }
}
