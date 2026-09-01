@php
    use App\Services\SiteSettings;
@endphp

<section class="relative min-h-screen overflow-hidden">
    <div class="absolute inset-0">
        <x-ui.parallax-image
            :src="SiteSettings::heroImage()"
            :alt="__('site.hero.image_alt')"
            fill
            speed="hero"
            :lazy="false"
            priority
            img-class="object-[70%_center]"
        />
        <div class="absolute inset-0 bg-gradient-to-r from-background via-background/90 to-background/40"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-background/30"></div>
    </div>

    <div class="pointer-events-none absolute right-8 top-1/2 hidden -translate-y-1/2 flex-col items-center gap-4 lg:flex">
        <span class="text-[10px] font-medium uppercase tracking-[0.3em] text-muted [writing-mode:vertical-rl]">{{ __('site.hero.scroll') }}</span>
        <span class="h-16 w-px bg-gradient-to-b from-cyan/50 to-transparent"></span>
    </div>

    <div class="container-site relative flex min-h-screen flex-col justify-center pb-24 pt-28 lg:pt-32">
        <div class="max-w-3xl">
            <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-cyan/30 px-4 py-2 text-[10px] font-semibold uppercase tracking-[0.15em] text-cyan sm:text-xs">
                <span>✦</span>
                <span>{{ __('site.hero.badge') }}</span>
            </div>

            <h1 class="text-4xl font-extrabold leading-[1.05] tracking-tight sm:text-5xl md:text-6xl lg:text-7xl">
                <span class="block text-white">{{ __('site.hero.line_1') }}</span>
                @if (__('site.hero.line_2'))
                    <span class="block text-white">{{ __('site.hero.line_2') }}</span>
                @endif
                <span class="block text-gradient-primary">{{ __('site.hero.line_3') }}</span>
                <span class="block text-cyan">{{ __('site.hero.line_4') }}</span>
            </h1>

            <p class="mt-6 max-w-xl text-base leading-relaxed text-muted sm:text-lg">
                {{ __('site.hero.subtitle') }}
            </p>

            <div class="mt-10 flex flex-col gap-4 sm:flex-row sm:items-center">
                <x-ui.button href="{{ localized_route('contact') }}" class="glow-purple">
                    {{ __('site.buttons.check_availability') }}
                </x-ui.button>

                <x-ui.button href="{{ SiteSettings::showreelUrl() ?? '#live-experience' }}" variant="outline">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full border border-white/20">
                        <svg class="ml-0.5 h-3 w-3 fill-white" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </span>
                    {{ __('site.buttons.watch_live') }}
                </x-ui.button>
            </div>
        </div>
    </div>
</section>
