@php
    use App\Models\BandMember;
    use App\Services\SiteSettings;
    $members = BandMember::active()->ordered()->get();
@endphp

<section id="about" class="py-20 sm:py-28">
    <div class="container-site">
        <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
            <div class="relative">
                <div class="absolute -left-3 -top-3 h-16 w-16 border-l-2 border-t-2 border-purple"></div>
                <div class="absolute -bottom-3 -right-3 h-4 w-4 rounded-full bg-cyan shadow-[0_0_20px_rgba(34,211,238,0.6)]"></div>
                <x-ui.parallax-image
                    :src="SiteSettings::aboutImage()"
                    :alt="site_t('about.image_alt')"
                    class="relative aspect-[4/5] w-full rounded-2xl"
                    speed="subtle"
                />
            </div>

            <div>
                <p class="section-eyebrow text-purple">{{ site_t('about.eyebrow') }}</p>
                <h2 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-4xl lg:text-5xl">
                    {{ site_t('about.title') }}
                </h2>

                <div class="mt-6 space-y-4 text-base leading-relaxed text-white/80">
                    <p>{{ SiteSettings::get('about_paragraph_1') }}</p>
                    <p>{{ SiteSettings::get('about_paragraph_2') }}</p>
                </div>

                <blockquote class="mt-8 border-l-2 border-cyan bg-surface/50 py-4 pl-6 pr-4 text-white/90 italic">
                    "{{ SiteSettings::get('about_quote') }}"
                </blockquote>

                <div class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-2">
                    @foreach ($members as $member)
                        <div class="flex items-center gap-3 text-sm text-white/80">
                            <span class="h-1.5 w-1.5 shrink-0 bg-cyan"></span>
                            {{ $member->role }}
                        </div>
                    @endforeach
                </div>

                <div class="mt-10">
                    <x-ui.button href="{{ localized_route('band') }}" variant="outline">
                        {{ site_t('buttons.meet_the_band') }}
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>
</section>
