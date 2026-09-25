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
        if ($this->app->runningInConsole()) {
            return;
        }

        $root = request()->getSchemeAndHttpHost();

        if (! filled($root)) {
            return;
        }

        // Filament/FilePond fetches existing files via XHR. If APP_URL is apex
        // (poprockavenue.nl) but the admin runs on www, that is a cross-origin
        // request without CORS → eternal "Waiting for size".
        URL::forceRootUrl($root);
        URL::forceScheme(str_starts_with($root, 'https://') ? 'https' : 'http');
        config(['filesystems.disks.public.url' => rtrim($root, '/').'/storage']);
    }
}
