<x-layout.app
    :title="site_t('meta.home_title')"
    :description="site_t('meta.default_description')"
>
    @include('pages.home.sections.hero')
    <x-ui.marquee :items="array_values(\App\Support\SiteCopy::section('marquee'))" />
    @if (count($stats) > 0)
        <x-sections.stats :stats="$stats" />
    @endif
    @include('pages.home.sections.events', ['liveEvents' => $liveEvents])
    @include('pages.home.sections.live-experience')
    @include('pages.home.sections.about')
    @include('pages.home.sections.services', ['eventTypes' => $eventTypes])
    @if ($mediaMoments->isNotEmpty())
        @include('pages.home.sections.live-moments', ['mediaMoments' => $mediaMoments])
    @endif
    @if ($testimonials->isNotEmpty())
        @include('pages.home.sections.testimonials', ['testimonials' => $testimonials])
    @endif
    @include('pages.home.sections.booking-cta')
</x-layout.app>
