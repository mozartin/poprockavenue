<section id="live-moments" class="py-20 sm:py-28">
    <div class="container-site space-y-12">
        <x-ui.section-header
            :eyebrow="site_t('live_moments.eyebrow')"
            eyebrowColor="text-cyan"
            :title="site_t('live_moments.title')"
            :subtitle="site_t('live_moments.subtitle')"
        />

        <div class="-mx-4 flex gap-4 overflow-x-auto px-4 pb-2 snap-x snap-mandatory sm:mx-0 sm:grid sm:grid-cols-3 sm:gap-6 sm:overflow-visible sm:px-0 lg:grid-cols-4">
            @foreach ($mediaMoments as $moment)
                <div class="w-[68vw] max-w-[280px] shrink-0 snap-center sm:w-auto sm:max-w-none">
                    <x-cards.media-moment :moment="$moment" />
                </div>
            @endforeach
        </div>

        <div class="pt-4 text-center">
            <x-ui.button href="{{ localized_route('media') }}" variant="outline">
                {{ site_t('buttons.view_all_moments') }}
            </x-ui.button>
        </div>
    </div>
</section>
