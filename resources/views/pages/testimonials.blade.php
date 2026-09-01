<x-layout.app
    :title="__('site.meta.testimonials_title')"
    :description="__('site.meta.testimonials_description')"
>
    <x-sections.page-hero
        :eyebrow="__('site.testimonials_section.eyebrow')"
        eyebrowColor="text-cyan"
        :title="__('site.testimonials_section.title')"
        :subtitle="__('site.testimonials_section.page_subtitle')"
    />

    <section class="pb-20 sm:pb-28">
        <div class="container-site">
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($testimonials as $testimonial)
                    <x-cards.testimonial :testimonial="$testimonial" />
                @endforeach
            </div>
        </div>
    </section>
</x-layout.app>
