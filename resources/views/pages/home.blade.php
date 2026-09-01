<x-layout.app
    :title="__('site.meta.home_title')"
    :description="__('site.meta.default_description')"
>
    @include('pages.home.sections.hero')
    <x-ui.marquee :items="array_values(__('site.marquee'))" />
    <x-sections.stats :stats="$stats" />
    @include('pages.home.sections.live-experience')
    @include('pages.home.sections.about')
    @include('pages.home.sections.events', ['eventTypes' => $eventTypes])
    @include('pages.home.sections.repertoire', ['repertoireCategories' => $repertoireCategories])
    @include('pages.home.sections.testimonials', ['testimonials' => $testimonials])
    @include('pages.home.sections.booking-cta')
</x-layout.app>
