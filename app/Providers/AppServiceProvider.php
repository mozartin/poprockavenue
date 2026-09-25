<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $appUrl = (string) config('app.url');
        $preferHttps = str_starts_with($appUrl, 'https://')
            || $this->app->environment('production');

        if ($preferHttps) {
            URL::forceScheme('https');
        }

        if ($this->app->runningInConsole()) {
            return;
        }

        // Use the public host the browser is on (www vs apex), but NEVER trust
        // request()->getScheme() here — TrustProxies has not run yet in boot(),
        // so Coolify's internal HTTP would generate http:// asset URLs and the
        // Filament admin CSS is blocked as mixed content on https pages.
        $host = request()->getHost() ?: parse_url($appUrl, PHP_URL_HOST);

        if (! is_string($host) || $host === '') {
            return;
        }

        $root = ($preferHttps ? 'https' : 'http').'://'.$host;
        URL::forceRootUrl($root);
        config(['filesystems.disks.public.url' => rtrim($root, '/').'/storage']);
    }
}
