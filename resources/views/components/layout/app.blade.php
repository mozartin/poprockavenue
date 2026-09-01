<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? __('site.meta.default_title') }}</title>
    <meta name="description" content="{{ $description ?? __('site.meta.default_description') }}">

    <meta property="og:title" content="{{ $title ?? __('site.meta.default_title') }}">
    <meta property="og:description" content="{{ $description ?? __('site.meta.default_description') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/hero.jpg') }}">

    @foreach (supported_locales() as $code => $label)
        <link rel="alternate" hreflang="{{ $code === 'uk' ? 'uk' : $code }}" href="{{ switch_locale_url($code) }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ switch_locale_url('en') }}">

    <link rel="icon" href="/favicon.ico" sizes="any">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-background text-white">
    <x-layout.header />

    <main>
        {{ $slot }}
    </main>

    <x-layout.footer />

    @stack('scripts')
</body>
</html>
