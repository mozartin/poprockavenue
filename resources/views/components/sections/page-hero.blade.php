@props([
    'eyebrow' => null,
    'eyebrowColor' => 'text-cyan',
    'title',
    'subtitle' => null,
    'image' => null,
])

<section class="relative overflow-hidden pt-28 pb-16 sm:pt-32 sm:pb-20">
    @if ($image)
        <div class="absolute inset-0">
            <x-ui.parallax-image
                :src="$image"
                alt=""
                fill
                speed="medium"
                img-class="opacity-20"
                aria-hidden="true"
            />
            <div class="absolute inset-0 bg-gradient-to-b from-background via-background/90 to-background"></div>
        </div>
    @endif

    <div class="container-site relative">
        @if ($eyebrow)
            <p class="section-eyebrow {{ $eyebrowColor }}">{{ $eyebrow }}</p>
        @endif
        <h1 class="mt-4 max-w-4xl text-3xl font-bold tracking-tight text-white sm:text-4xl lg:text-5xl">
            {{ $title }}
        </h1>
        @if ($subtitle)
            <p class="mt-6 max-w-2xl text-lg text-muted">{{ $subtitle }}</p>
        @endif
    </div>
</section>
