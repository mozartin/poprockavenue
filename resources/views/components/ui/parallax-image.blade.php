@props([
    'src',
    'alt' => '',
    'speed' => 'medium',
    'fill' => false,
    'lazy' => true,
    'priority' => false,
    'imgClass' => '',
])

@php
    $speeds = [
        'subtle' => 0.12,
        'medium' => 0.22,
        'strong' => 0.32,
        'hero' => 0.42,
    ];

    $speedValue = is_numeric($speed) ? $speed : ($speeds[$speed] ?? 0.22);
@endphp

<div
    data-parallax
    data-parallax-speed="{{ $speedValue }}"
    {{ $attributes->class([
        'overflow-hidden',
        'absolute inset-0' => $fill,
        'relative' => ! $fill,
    ]) }}
>
    <div data-parallax-target class="absolute -top-[10%] left-0 h-[120%] w-full">
        <img
            src="{{ $src }}"
            alt="{{ $alt }}"
            @class([
                'h-full w-full object-cover',
                $imgClass,
            ])
            @if ($lazy && ! $priority) loading="lazy" @endif
            @if ($priority) fetchpriority="high" @endif
        >
    </div>
</div>
