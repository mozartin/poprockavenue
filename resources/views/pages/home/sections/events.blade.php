<section id="events" class="py-20 sm:py-28">
    <div class="container-site space-y-12">
        <x-ui.section-header
            :eyebrow="site_t('events_section.eyebrow')"
            eyebrowColor="text-cyan"
            :title="site_t('events_section.title')"
            :subtitle="site_t('events_section.subtitle')"
        />

        @if ($liveEvents->isEmpty())
            <p class="text-center text-sm text-muted">{{ site_t('events_section.empty') }}</p>
        @else
            <div class="space-y-6">
                @foreach ($liveEvents as $event)
                    <x-cards.live-event :event="$event" />
                @endforeach
            </div>
        @endif
    </div>
</section>
