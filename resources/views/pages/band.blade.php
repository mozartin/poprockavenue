@php
    use App\Services\SiteSettings;
@endphp

<x-layout.app
    :title="site_t('meta.band_title')"
    :description="site_t('meta.band_description')"
>
    <x-sections.page-hero
        :eyebrow="site_t('about.eyebrow')"
        :title="site_t('about.page_title')"
        :subtitle="site_t('about.page_subtitle')"
        :image="SiteSettings::aboutImage()"
    />

    <section class="pb-20 sm:pb-28">
        <div class="container-site">
            <div class="grid gap-8 lg:grid-cols-2">
                <div class="space-y-4 text-base leading-relaxed text-white/80">
                    <p>{{ SiteSettings::get('about_paragraph_1') }}</p>
                    <p>{{ SiteSettings::get('about_paragraph_2') }}</p>
                </div>

                <blockquote class="border-l-2 border-cyan bg-surface/50 py-4 pl-6 pr-4 text-lg text-white/90 italic">
                    "{{ SiteSettings::get('about_quote') }}"
                </blockquote>
            </div>

            <div class="mt-16">
                @if ($members->isEmpty())
                    <p class="text-center text-sm text-muted">{{ site_t('about.members_empty') }}</p>
                @else
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        @foreach ($members as $member)
                            <x-cards.band-member :member="$member" />
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-16 text-center">
                <x-ui.button href="{{ localized_route('contact') }}">{{ site_t('buttons.check_availability') }}</x-ui.button>
            </div>
        </div>
    </section>
</x-layout.app>
