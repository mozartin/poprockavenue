@props(['event', 'size' => 'default'])

@php
    $sizeClasses = match ($size) {
        'large' => 'md:row-span-2 min-h-[420px]',
        'wide' => 'md:col-span-2 min-h-[280px]',
        default => 'min-h-[220px]',
    };
@endphp

<a
    href="{{ localized_route('contact', ['event_type' => $event->name]) }}#event_type"
    class="group relative flex overflow-hidden rounded-2xl {{ $sizeClasses }}"
>
    <x-ui.parallax-image
        :src="$event->imageUrl()"
        :alt="$event->title"
        fill
        speed="subtle"
        img-class="transition-transform duration-700 group-hover:scale-105"
    />
    <div class="absolute inset-0 bg-gradient-to-t from-background via-background/60 to-background/20"></div>

    <div class="relative mt-auto p-6 sm:p-8">
        <span class="mb-3 block h-0.5 w-8 rounded-full" style="background-color: {{ $event->accent_color }}"></span>
        <h3 class="text-xl font-bold text-white sm:text-2xl">{{ $event->title }}</h3>
        <p class="mt-2 max-w-md text-sm text-muted">{{ $event->subtitle }}</p>
    </div>
</a>
