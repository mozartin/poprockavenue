@php
    $pageTitle = $title ?? site_t('meta.default_title');
    $pageDescription = $description ?? site_t('meta.default_description');
    $canonicalUrl = url()->current();
    $ogImage = \App\Services\SiteSettings::ogImage();
    $ogImageUrl = str_starts_with($ogImage, 'http') ? $ogImage : url($ogImage);
    $ogSiteName = \App\Services\SiteSettings::ogSiteName();
    $locale = app()->getLocale();
    $ogLocale = match ($locale) {
        'nl' => 'nl_NL',
        'uk' => 'uk_UA',
        'ru' => 'ru_RU',
        default => 'en_GB',
    };
    $sameAs = array_values(array_filter([
        \App\Services\SiteSettings::instagram(),
        \App\Services\SiteSettings::tiktok(),
        \App\Services\SiteSettings::youtube(),
    ]));
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'MusicGroup',
        'name' => $ogSiteName,
        'alternateName' => 'Pop Rock Avenue',
        'url' => rtrim(config('app.url'), '/'),
        'image' => $ogImageUrl,
        'description' => $pageDescription,
        'email' => \App\Services\SiteSettings::email(),
        'telephone' => \App\Services\SiteSettings::phone(),
        'areaServed' => [
            '@type' => 'Country',
            'name' => 'Netherlands',
        ],
        'genre' => ['Pop', 'Rock', 'Dance', 'Cover'],
        'sameAs' => $sameAs,
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <meta property="og:site_name" content="{{ $ogSiteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $ogImageUrl }}">
    <meta property="og:locale" content="{{ $ogLocale }}">
    @foreach (supported_locales() as $code => $label)
        @if ($code !== $locale)
            <meta property="og:locale:alternate" content="{{ match ($code) {
                'nl' => 'nl_NL',
                'uk' => 'uk_UA',
                'ru' => 'ru_RU',
                default => 'en_GB',
            } }}">
        @endif
    @endforeach

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $ogImageUrl }}">

    @foreach (supported_locales() as $code => $label)
        <link rel="alternate" hreflang="{{ $code }}" href="{{ switch_locale_url($code) }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ switch_locale_url('en') }}">

    <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP) !!}</script>

    {{-- Cache-bust so browsers pick up the new mark favicon --}}
    <link rel="icon" href="/favicon-32x32.png?v=mark2" type="image/png" sizes="32x32">
    <link rel="icon" href="/favicon-16x16.png?v=mark2" type="image/png" sizes="16x16">
    <link rel="shortcut icon" href="/favicon-32x32.png?v=mark2">
    <link rel="icon" href="/favicon.svg?v=mark2" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png?v=mark2" sizes="180x180">
    <link rel="icon" href="/icon-192.png?v=mark2" type="image/png" sizes="192x192">
    <link rel="icon" href="/icon-512.png?v=mark2" type="image/png" sizes="512x512">
    <meta name="theme-color" content="#08090D">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen overflow-x-clip bg-background text-white">
    <x-layout.header />

    <main class="min-w-0 overflow-x-clip">
        {{ $slot }}
    </main>

    <x-layout.footer />

    @stack('scripts')
</body>
</html>
