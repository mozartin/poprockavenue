@php
    use App\Services\SiteSettings;

    $showreelUrl = SiteSettings::showreelUrl();
    $youtubeId = SiteSettings::showreelYoutubeId();
    $poster = SiteSettings::liveVideoImage();
    $embedSrc = $youtubeId
        ? 'https://www.youtube-nocookie.com/embed/'.$youtubeId.'?autoplay=1&rel=0&modestbranding=1'
        : null;
@endphp

<section id="live-experience" class="py-20 sm:py-28">
    <div class="container-site">
        <x-ui.section-header
            :eyebrow="site_t('live_experience.eyebrow')"
            eyebrowColor="text-purple"
            :title="site_t('live_experience.title')"
            :subtitle="site_t('live_experience.subtitle')"
        />

        <div
            class="mx-auto mt-16 w-full max-w-5xl sm:mt-20"
            @if ($embedSrc)
                x-data="{ playing: false }"
            @endif
        >
            <div class="relative aspect-video overflow-hidden rounded-xl bg-black shadow-[0_20px_60px_rgba(0,0,0,0.55)] ring-1 ring-white/10">
                @if ($embedSrc)
                    <template x-if="playing">
                        <iframe
                            class="absolute inset-0 h-full w-full"
                            src="{{ $embedSrc }}"
                            title="{{ site_t('live_experience.play_label') }}"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                            referrerpolicy="strict-origin-when-cross-origin"
                        ></iframe>
                    </template>

                    <button
                        type="button"
                        class="group absolute inset-0 w-full text-left"
                        x-show="!playing"
                        @click="playing = true"
                        aria-label="{{ site_t('live_experience.play_label') }}"
                    >
                        <img
                            src="{{ $poster }}"
                            alt="{{ site_t('live_experience.image_alt') }}"
                            class="absolute inset-0 h-full w-full object-cover"
                            loading="lazy"
                        >

                        <span class="absolute left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center justify-center transition-transform group-hover:scale-110">
                            <svg class="h-16 w-[88px] drop-shadow-lg sm:h-[68px] sm:w-[96px]" viewBox="0 0 68 48" aria-hidden="true">
                                <path
                                    class="fill-[#FF0033] opacity-90 transition-opacity group-hover:opacity-100"
                                    d="M66.52 7.74a8 8 0 0 0-5.64-5.66C56.73 1.04 34 1.04 34 1.04s-22.73 0-26.88 1.04A8 8 0 0 0 1.48 7.74C.44 11.9.44 24 .44 24s0 12.1 1.04 16.26a8 8 0 0 0 5.64 5.66C11.27 46.96 34 46.96 34 46.96s22.73 0 26.88-1.04a8 8 0 0 0 5.64-5.66C67.56 36.1 67.56 24 67.56 24s0-12.1-1.04-16.26z"
                                />
                                <path class="fill-white" d="M45 24 27.02 34.27V13.73z" />
                            </svg>
                        </span>
                    </button>
                @else
                    <a
                        href="{{ $showreelUrl ?? '#' }}"
                        @if ($showreelUrl) target="_blank" rel="noopener noreferrer" @endif
                        class="group absolute inset-0 block"
                        aria-label="{{ site_t('live_experience.play_label') }}"
                    >
                        <img
                            src="{{ $poster }}"
                            alt="{{ site_t('live_experience.image_alt') }}"
                            class="absolute inset-0 h-full w-full object-cover"
                            loading="lazy"
                        >
                        <span class="absolute left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 transition-transform group-hover:scale-110">
                            <svg class="h-16 w-[88px] drop-shadow-lg sm:h-[68px] sm:w-[96px]" viewBox="0 0 68 48" aria-hidden="true">
                                <path class="fill-[#FF0033] opacity-90 group-hover:opacity-100" d="M66.52 7.74a8 8 0 0 0-5.64-5.66C56.73 1.04 34 1.04 34 1.04s-22.73 0-26.88 1.04A8 8 0 0 0 1.48 7.74C.44 11.9.44 24 .44 24s0 12.1 1.04 16.26a8 8 0 0 0 5.64 5.66C11.27 46.96 34 46.96 34 46.96s22.73 0 26.88-1.04a8 8 0 0 0 5.64-5.66C67.56 36.1 67.56 24 67.56 24s0-12.1-1.04-16.26z"/>
                                <path class="fill-white" d="M45 24 27.02 34.27V13.73z"/>
                            </svg>
                        </span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
