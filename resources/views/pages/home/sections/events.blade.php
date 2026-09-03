<section id="events" class="py-20 sm:py-28">
    <div class="container-site space-y-12">
        <x-ui.section-header
            :eyebrow="site_t('events_section.eyebrow')"
            eyebrowColor="text-cyan"
            :title="site_t('events_section.title')"
        />

        @php
            $weddings = $eventTypes->firstWhere('slug', 'weddings');
            $corporate = $eventTypes->firstWhere('slug', 'corporate-events');
            $private = $eventTypes->firstWhere('slug', 'private-parties');
            $christmas = $eventTypes->firstWhere('slug', 'christmas-new-year');
        @endphp

        <div class="grid gap-4 md:grid-cols-2 md:grid-rows-2 lg:gap-5">
            @if ($weddings)
                <x-cards.event-type :event="$weddings" size="large" class="md:row-span-2" />
            @endif
            @if ($corporate)
                <x-cards.event-type :event="$corporate" />
            @endif
            @if ($private)
                <x-cards.event-type :event="$private" />
            @endif
            @if ($christmas)
                <div class="md:col-span-2">
                    <x-cards.event-type :event="$christmas" size="wide" />
                </div>
            @endif
        </div>
    </div>
</section>
