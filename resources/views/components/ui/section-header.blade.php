@props([
    'eyebrow' => null,
    'eyebrowColor' => 'text-purple',
    'title',
    'subtitle' => null,
    'align' => 'left',
])

@php
    $alignment = match ($align) {
        'center' => 'text-center items-center',
        'right' => 'text-right items-end',
        default => 'text-left items-start',
    };
@endphp

<div {{ $attributes->merge(['class' => "flex flex-col gap-4 {$alignment}"]) }}>
    @if ($eyebrow)
        <p class="section-eyebrow {{ $eyebrowColor }}">{{ $eyebrow }}</p>
    @endif

    <div class="flex w-full flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
        <h2 class="max-w-3xl text-3xl font-bold tracking-tight text-white sm:text-4xl lg:text-5xl">
            {{ $title }}
        </h2>

        @if ($subtitle)
            <p class="max-w-sm text-sm leading-relaxed text-muted lg:text-right">
                {{ $subtitle }}
            </p>
        @endif
    </div>
</div>
