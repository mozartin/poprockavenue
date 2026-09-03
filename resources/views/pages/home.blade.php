<x-layout.app
    :title="site_t('meta.home_title')"
    :description="site_t('meta.default_description')"
>
    @include('pages.home.sections.hero')
    <x-ui.marquee :items="array_values(\App\Support\SiteCopy::section('marquee'))" />
    <x-sections.stats :stats="$stats" />
    @include('pages.home.sections.live-experience')
    @include('pages.home.sections.about')
    @include('pages.home.sections.events', ['eventTypes' => $eventTypes])
    @include('pages.home.sections.repertoire', ['repertoireCategories' => $repertoireCategories])
    @include('pages.home.sections.testimonials', ['testimonials' => $testimonials])
    @include('pages.home.sections.booking-cta')
</x-layout.app>
