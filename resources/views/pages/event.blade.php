@php
    $heroImage = $event->heroImageUrl();
@endphp

<x-layout.app
    :title="$event->meta_title ?? $event->title . ' — Pop Rock Avenue'"
    :description="$event->meta_description ?? $event->description"
>
    <x-sections.page-hero
        :eyebrow="$event->name"
        eyebrowColor="text-cyan"
        :title="$event->title"
        :subtitle="$event->subtitle"
        :image="$heroImage"
    />

    <section class="pb-20 sm:pb-28">
        <div class="container-site">
            <div class="grid items-start gap-12 lg:grid-cols-2">
                <div class="prose prose-invert max-w-none prose-p:text-white/80 prose-headings:text-white">
                    {!! $event->content !!}
                </div>

                <x-ui.parallax-image
                    :src="$event->imageUrl()"
                    :alt="$event->title"
                    class="aspect-[4/3] overflow-hidden rounded-2xl"
                    speed="subtle"
                />
            </div>

            <div class="mt-16 rounded-2xl bg-surface p-8 text-center sm:p-12">
                <h2 class="text-2xl font-bold text-white sm:text-3xl">{{ site_t('event_page.cta_title') }}</h2>
                <p class="mx-auto mt-4 max-w-xl text-muted">
                    {{ site_t('event_page.cta_text', ['event' => strtolower($event->name)]) }}
                </p>
                <div class="mt-8">
                    <x-ui.button :href="localized_route('contact', ['event_type' => $event->name])">
                        {{ site_t('buttons.check_availability') }}
                    </x-ui.button>
                </div>
            </div>
        </div>
    </section>
</x-layout.app>
