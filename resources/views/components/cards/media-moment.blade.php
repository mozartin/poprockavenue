@props([
    'moment',
    'class' => '',
])

@php
    $poster = $moment->posterUrl();
    $title = $moment->title;
    $videoUrl = $moment->videoUrl();
@endphp

<div
    {{ $attributes->class(['contents']) }}
    x-data="{
        open: false,
        openPlayer() {
            this.open = true;
            document.documentElement.classList.add('media-moment-open');
        },
        closePlayer() {
            this.open = false;
            document.documentElement.classList.remove('media-moment-open');
        },
    }"
    @keydown.escape.window="if (open) closePlayer()"
>
    <button
        type="button"
        @click="openPlayer()"
        class="{{ trim('group relative block w-full overflow-hidden rounded-2xl border border-white/10 bg-surface aspect-[9/16] text-left '.$class) }}"
        aria-label="{{ $title ? 'Play '.$title : 'Play video' }}"
    >
        @if ($poster)
            <img
                src="{{ $poster }}"
                alt=""
                class="pointer-events-none absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-[1.03]"
                loading="lazy"
                decoding="async"
                aria-hidden="true"
            >
        @else
            {{-- No poster: show the video's first frame. Hidden while the modal player is open. --}}
            <video
                class="pointer-events-none absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-[1.03]"
                muted
                playsinline
                preload="metadata"
                aria-hidden="true"
                x-show="! open"
                x-init="
                    const seekFirstFrame = () => {
                        try { $el.currentTime = 0.1; } catch (_) {}
                    };
                    $el.addEventListener('loadeddata', seekFirstFrame, { once: true });
                    if ($el.readyState >= 2) seekFirstFrame();
                "
            >
                <source src="{{ $videoUrl }}" type="video/mp4">
            </video>
        @endif

        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-background/80 via-transparent to-transparent"></div>

        @if ($title)
            <span class="absolute inset-x-0 bottom-0 z-[1] p-4 text-sm font-semibold text-white">
                {{ $title }}
            </span>
        @endif

        <span class="pointer-events-none absolute inset-0 z-[1] flex items-center justify-center">
            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-r from-purple to-cyan shadow-[0_0_30px_rgba(167,139,250,0.45)] transition-transform group-hover:scale-110">
                <svg class="ml-0.5 h-5 w-5 fill-white" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
            </span>
        </span>
    </button>

    <template x-teleport="body">
        <div
            x-show="open"
            x-cloak
            class="fixed inset-0 z-[100000] flex items-center justify-center bg-background/95 p-4 sm:p-8"
            role="dialog"
            aria-modal="true"
            :aria-label="{{ Js::from($title ?: 'Video player') }}"
            @click.self="closePlayer()"
        >
            {{-- No overflow:hidden / transform around <video>: Chromium paints a ghost frame otherwise. --}}
            <div class="relative w-full max-w-[min(100vw-2rem,960px)] rounded-2xl border border-white/10 bg-black shadow-[0_0_60px_rgba(167,139,250,0.25)]" @click.stop>
                <button
                    type="button"
                    class="absolute right-3 top-3 z-20 flex h-9 w-9 items-center justify-center rounded-full border border-white/15 bg-background/80 text-white hover:bg-background"
                    @click="closePlayer()"
                    aria-label="Close"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <template x-if="open">
                    <video
                        data-moment-player
                        @class([
                            'block max-h-[min(85vh,900px)] w-full bg-black',
                            'rounded-t-2xl' => filled($title),
                            'rounded-2xl' => blank($title),
                        ])
                        controls
                        playsinline
                        preload="metadata"
                        disablepictureinpicture
                        controlslist="nodownload"
                        @if ($poster) poster="{{ $poster }}" @endif
                        x-init="$nextTick(() => { $el.play().catch(() => {}) })"
                    >
                        <source src="{{ $videoUrl }}" type="video/mp4">
                    </video>
                </template>

                @if ($title)
                    <div class="rounded-b-2xl border-t border-white/10 bg-surface px-4 py-3">
                        <p class="text-sm font-semibold text-white">{{ $title }}</p>
                    </div>
                @endif
            </div>
        </div>
    </template>
</div>
