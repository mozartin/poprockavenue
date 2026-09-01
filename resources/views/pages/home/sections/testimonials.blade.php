<section id="testimonials" class="py-20 sm:py-28">
    <div class="container-site space-y-12">
        <div class="text-center">
            <p class="section-eyebrow text-cyan">{{ __('site.testimonials_section.eyebrow') }}</p>
            <h2 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-4xl lg:text-5xl">
                {{ __('site.testimonials_section.title') }}
            </h2>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($testimonials as $testimonial)
                <x-cards.testimonial :testimonial="$testimonial" />
            @endforeach
        </div>
    </div>
</section>
