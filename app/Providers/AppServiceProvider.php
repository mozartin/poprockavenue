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
        $appUrl = config('app.url');

        if (is_string($appUrl) && str_starts_with($appUrl, 'https://')) {
            URL::forceScheme('https');
        }

        // Keep generated asset/storage URLs on the same host the admin is using
        // (www vs apex), otherwise FilePond/Livewire previews hang.
        if (! $this->app->runningInConsole() && request()->getHost()) {
            $root = request()->getSchemeAndHttpHost();
            if (filled($root)) {
                URL::forceRootUrl($root);
            }
        }
    }
}
