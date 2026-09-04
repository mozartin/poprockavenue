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
                            <div class="rounded-2xl bg-surface p-6">
                                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-purple/20 to-cyan/20 text-cyan">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                                </div>
                                <h3 class="font-semibold text-white">{{ $member->role }}</h3>
                                @if ($member->name)
                                    <p class="mt-1 text-sm text-muted">{{ $member->name }}</p>
                                @endif
                                @if ($member->bio)
                                    <p class="mt-3 text-sm leading-relaxed text-muted">{{ $member->bio }}</p>
                                @endif
                            </div>
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
