<x-layout.app
    :title="site_t('meta.testimonials_title')"
    :description="site_t('meta.testimonials_description')"
>
    <x-sections.page-hero
        :eyebrow="site_t('testimonials_section.eyebrow')"
        eyebrowColor="text-cyan"
        :title="site_t('testimonials_section.title')"
        :subtitle="site_t('testimonials_section.page_subtitle')"
    />

    <section class="pb-20 sm:pb-28">
        <div class="container-site">
            @if ($testimonials->isEmpty())
                <p class="text-center text-sm text-muted">{{ site_t('testimonials_section.empty') }}</p>
            @else
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($testimonials as $testimonial)
                        <x-cards.testimonial :testimonial="$testimonial" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-layout.app>
