@props([
    'href' => '#',
    'variant' => 'primary',
    'type' => 'link',
])

@php
    $classes = match ($variant) {
        'primary' => 'btn-gradient',
        'outline' => 'btn-outline',
        default => 'btn-gradient',
    };
@endphp

@if ($type === 'button')
    <button {{ $attributes->merge(['type' => 'submit', 'class' => $classes]) }}>
        {{ $slot }}
    </button>
@else
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@endif
