@props(['category', 'index'])

@php
    $featuredSongs = $category->songs->where('is_featured', true)->take(5);
@endphp

<div class="grid items-center gap-4 border-b border-white/5 py-8 md:grid-cols-[auto_1fr_2fr] lg:grid-cols-[3rem_12rem_1fr_2fr]">
    <span class="hidden text-sm font-medium lg:block" style="color: {{ $category->accent_color }}80">
        {{ str_pad($index, 2, '0', STR_PAD_LEFT) }}
    </span>

    <h3 class="text-2xl font-bold sm:text-3xl" style="color: {{ $category->accent_color }}">
        {{ $category->name }}
    </h3>

    <div class="hidden h-px bg-white/5 md:block"></div>

    <div class="flex flex-wrap items-center gap-2">
        @foreach ($featuredSongs as $song)
            <span class="rounded-full bg-surface px-3 py-1.5 text-xs text-muted">
                {{ $song->artist }}
            </span>
        @endforeach
        <a href="{{ localized_route('repertoire') }}#{{ $category->slug }}" class="text-xs font-medium" style="color: {{ $category->accent_color }}">
            {{ __('site.repertoire_section.more') }}
        </a>
    </div>
</div>
