<?php

if (! function_exists('supported_locales')) {
    function supported_locales(): array
    {
        return config('app.supported_locales', ['en' => 'EN']);
    }
}

if (! function_exists('localized_route')) {
    function localized_route(string $name, array $parameters = [], ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return route($name, array_merge(['locale' => $locale], $parameters));
    }
}

if (! function_exists('switch_locale_url')) {
    function switch_locale_url(string $locale): string
    {
        $route = request()->route();

        if (! $route || ! $route->getName()) {
            return localized_route('home', [], $locale);
        }

        $parameters = $route->parameters();
        $parameters['locale'] = $locale;

        return route($route->getName(), $parameters);
    }
}

if (! function_exists('site_t')) {
    /**
     * Editable site copy (DB) with fallback to lang/site.php.
     * Example: site_t('hero.line_1') or site_t('event_page.cta_text', ['event' => 'wedding'])
     */
    function site_t(string $key, array $replace = [], ?string $default = null): string
    {
        return \App\Support\SiteCopy::get($key, $replace, $default);
    }
}
