<x-layout.app
    :title="site_t('meta.media_title')"
    :description="site_t('meta.media_description')"
>
    <x-sections.page-hero
        :eyebrow="site_t('live_moments.eyebrow')"
        :title="site_t('live_moments.page_title')"
        :subtitle="site_t('live_moments.page_subtitle')"
    />

    <section class="pb-20 sm:pb-28">
        <div class="container-site">
            @if ($mediaMoments->isEmpty())
                <p class="text-center text-sm text-muted">{{ site_t('live_moments.empty') }}</p>
            @else
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 lg:gap-6">
                    @foreach ($mediaMoments as $moment)
                        <x-cards.media-moment :moment="$moment" />
                    @endforeach
                </div>
            @endif

            <div class="mt-12 text-center">
                <x-ui.button href="{{ localized_route('contact') }}">
                    {{ site_t('buttons.check_availability') }}
                </x-ui.button>
            </div>
        </div>
    </section>
</x-layout.app>
