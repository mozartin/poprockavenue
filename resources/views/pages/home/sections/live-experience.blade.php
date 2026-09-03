@php
    use App\Services\SiteSettings;
@endphp

<section id="live-experience" class="py-20 sm:py-28">
    <div class="container-site space-y-12">
        <x-ui.section-header
            :eyebrow="site_t('live_experience.eyebrow')"
            eyebrowColor="text-purple"
            :title="site_t('live_experience.title')"
            :subtitle="site_t('live_experience.subtitle')"
        />

        <div class="group relative aspect-video overflow-hidden rounded-2xl">
            <x-ui.parallax-image
                :src="SiteSettings::liveVideoImage()"
                :alt="site_t('live_experience.image_alt')"
                fill
                speed="medium"
            />
            <div class="absolute inset-0 bg-background/30 transition-colors group-hover:bg-background/20"></div>

            <a
                href="{{ SiteSettings::showreelUrl() ?? '#' }}"
                target="_blank"
                rel="noopener noreferrer"
                class="absolute inset-0 flex flex-col items-center justify-center gap-4"
                aria-label="{{ site_t('live_experience.play_label') }}"
            >
                <span class="flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-r from-cyan to-purple shadow-[0_0_40px_rgba(34,211,238,0.4)] transition-transform group-hover:scale-110">
                    <svg class="ml-1 h-8 w-8 fill-white" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </span>
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-white">{{ site_t('buttons.watch_showreel') }}</span>
            </a>
        </div>
    </div>
</section>
