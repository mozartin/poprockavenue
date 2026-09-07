@props(['member'])

@php
    /** @var \App\Models\BandMember $member */
    $photo = $member->imageUrl();
    $alt = $member->name ?: $member->role;
@endphp

<article class="group overflow-hidden rounded-2xl border border-white/10 bg-white/[0.03] transition-colors hover:border-white/20">
    @if ($photo)
        <div class="relative aspect-[4/5] overflow-hidden bg-background">
            <img
                src="{{ $photo }}"
                alt="{{ $alt }}"
                class="h-full w-full object-cover object-top transition-transform duration-700 group-hover:scale-105"
                loading="lazy"
            />
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-background via-background/25 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 p-5">
                <h3 class="text-lg font-semibold text-white">{{ $member->role }}</h3>
                @if ($member->name)
                    <p class="mt-1 text-sm font-medium text-cyan">{{ $member->name }}</p>
                @endif
            </div>
        </div>
        @if ($member->bio)
            <div class="border-t border-white/10 p-5">
                <p class="text-sm leading-relaxed text-muted">{{ $member->bio }}</p>
            </div>
        @endif
    @else
        <div class="p-6">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-purple/20 to-cyan/20 text-cyan">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                </svg>
            </div>
            <h3 class="font-semibold text-white">{{ $member->role }}</h3>
            @if ($member->name)
                <p class="mt-1 text-sm text-muted">{{ $member->name }}</p>
            @endif
            @if ($member->bio)
                <p class="mt-3 text-sm leading-relaxed text-muted">{{ $member->bio }}</p>
            @endif
        </div>
    @endif
</article>
