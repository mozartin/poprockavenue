@php
    use App\Services\SiteSettings;
@endphp

<section class="relative min-h-screen overflow-hidden">
    <div class="absolute inset-0">
        <x-ui.parallax-image
            :src="SiteSettings::heroImage()"
            :alt="site_t('hero.image_alt')"
            fill
            speed="hero"
            :lazy="false"
            priority
            img-class="hero-photo object-[center_35%] sm:object-[72%_center]"
        />
        {{-- Darken left for copy; keep the right side of the photo open and bright --}}
        <div class="absolute inset-0 bg-gradient-to-r from-background from-[5%] via-background/55 via-[38%] to-transparent to-[72%] max-sm:from-background/80 max-sm:via-background/40 max-sm:via-[55%] max-sm:to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-background/75 via-transparent to-transparent sm:from-background/55"></div>
    </div>

    <div class="pointer-events-none absolute right-8 top-1/2 hidden -translate-y-1/2 flex-col items-center gap-4 lg:flex">
        <img
            src="{{ asset('images/logo/logo-mark-v1-transparent.png') }}"
            alt=""
            width="40"
            height="39"
            class="mb-2 h-10 w-10 object-contain opacity-40"
            decoding="async"
            aria-hidden="true"
        >
        <span class="text-[10px] font-medium uppercase tracking-[0.3em] text-muted [writing-mode:vertical-rl]">{{ site_t('hero.scroll') }}</span>
        <span class="h-16 w-px bg-gradient-to-b from-cyan/50 to-transparent"></span>
    </div>

    <div class="container-site relative flex min-h-screen flex-col justify-center pb-24 pt-28 lg:pt-32">
        <div class="max-w-3xl [text-shadow:0_2px_28px_rgba(8,9,13,0.55)]">
            <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-cyan/30 bg-background/25 px-4 py-2 text-[10px] font-semibold uppercase tracking-[0.15em] text-cyan backdrop-blur-sm sm:text-xs">
                <span>✦</span>
                <span>{{ site_t('hero.badge') }}</span>
            </div>

            <h1 class="text-4xl font-extrabold leading-[1.05] tracking-tight break-words sm:text-5xl md:text-6xl lg:text-7xl">
                <span class="block text-white">{{ site_t('hero.line_1') }}</span>
                @if (site_t('hero.line_2'))
                    <span class="block text-white">{{ site_t('hero.line_2') }}</span>
                @endif
                <span class="block text-gradient-primary">{{ site_t('hero.line_3') }}</span>
                <span class="block text-cyan">{{ site_t('hero.line_4') }}</span>
            </h1>

            <p class="mt-6 max-w-xl text-base leading-relaxed text-white/80 sm:text-lg">
                {{ site_t('hero.subtitle') }}
            </p>

            <div class="mt-10 flex flex-col gap-4 sm:flex-row sm:items-center">
                <x-ui.button href="{{ localized_route('contact') }}" class="glow-purple">
                    {{ site_t('buttons.check_availability') }}
                </x-ui.button>

                <x-ui.button href="{{ SiteSettings::showreelUrl() ?? '#live-experience' }}" variant="outline">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full border border-white/20">
                        <svg class="ml-0.5 h-3 w-3 fill-white" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </span>
                    {{ site_t('buttons.watch_live') }}
                </x-ui.button>
            </div>
        </div>
    </div>
</section>
