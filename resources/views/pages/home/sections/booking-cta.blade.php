@php
    use App\Services\SiteSettings;
@endphp

<section class="relative overflow-hidden py-24 sm:py-32">
    <div class="absolute inset-0">
        <x-ui.parallax-image
            :src="SiteSettings::ctaBackgroundImage()"
            alt=""
            fill
            speed="strong"
            img-class="opacity-30"
            aria-hidden="true"
        />
        <div class="absolute inset-0 bg-gradient-to-b from-background via-background/80 to-background"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(124,58,237,0.15),transparent_70%)]"></div>
    </div>

    <div class="container-site relative text-center">
        <p class="section-eyebrow text-pink">{{ __('site.booking_cta.eyebrow') }}</p>

        <h2 class="mx-auto mt-4 max-w-3xl text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl">
            <span class="text-white">{{ __('site.booking_cta.title_1') }} </span>
            <span class="text-gradient-cta">{{ __('site.booking_cta.title_2') }}</span>
        </h2>

        <p class="mx-auto mt-6 max-w-xl text-base text-muted">
            {{ __('site.booking_cta.subtitle') }}
        </p>

        <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
            <x-ui.button href="{{ localized_route('contact') }}" class="glow-purple">
                {{ __('site.buttons.check_availability') }}
            </x-ui.button>
            <x-ui.button href="{{ SiteSettings::phoneLink() }}" variant="outline">
                {{ SiteSettings::phone() }}
            </x-ui.button>
        </div>

        <p class="mt-10 text-sm text-muted">
            {{ __('site.booking_cta.footer') }}
        </p>
    </div>
</section>
