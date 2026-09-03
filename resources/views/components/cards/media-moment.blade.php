@props([
    'moment',
    'class' => '',
])

@php
    $poster = $moment->posterUrl();
    $title = $moment->title;
@endphp

<div
    {{ $attributes->class(['contents']) }}
    x-data="{
        open: false,
        playPreview() {
            const video = this.$refs.preview;
            if (! video) return;
            video.muted = true;
            video.play().catch(() => {});
        },
        pausePreview() {
            const video = this.$refs.preview;
            if (! video) return;
            video.pause();
            video.currentTime = 0;
        },
        openPlayer() {
            this.pausePreview();
            this.open = true;
            this.$nextTick(() => {
                const player = this.$refs.player;
                if (! player) return;
                player.muted = false;
                player.currentTime = 0;
                player.play().catch(() => {});
            });
        },
        closePlayer() {
            this.open = false;
            const player = this.$refs.player;
            if (! player) return;
            player.pause();
            player.currentTime = 0;
        },
    }"
    @keydown.escape.window="if (open) closePlayer()"
>
    <button
        type="button"
        @click="openPlayer()"
        @mouseenter="playPreview()"
        @mouseleave="pausePreview()"
        class="{{ trim('group relative block w-full overflow-hidden rounded-2xl border border-white/10 bg-surface aspect-[9/16] text-left '.$class) }}"
        aria-label="{{ $title ? 'Play '.$title : 'Play video' }}"
    >
        <video
            class="pointer-events-none h-full w-full object-cover transition duration-700 group-hover:scale-[1.03]"
            playsinline
            muted
            loop
            preload="metadata"
            @if ($poster) poster="{{ $poster }}" @endif
            x-ref="preview"
        >
            <source src="{{ $moment->videoUrl() }}" type="video/mp4">
        </video>

        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-background/80 via-transparent to-transparent"></div>

        @if ($title)
            <span class="absolute inset-x-0 bottom-0 p-4 text-sm font-semibold text-white">
                {{ $title }}
            </span>
        @endif

        <span class="pointer-events-none absolute inset-0 flex items-center justify-center">
            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-r from-purple to-cyan shadow-[0_0_30px_rgba(124,58,237,0.45)] transition-transform group-hover:scale-110">
                <svg class="ml-0.5 h-5 w-5 fill-white" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
            </span>
        </span>
    </button>

    <template x-teleport="body">
        <div
            x-show="open"
            x-cloak
            class="fixed inset-0 z-[100000] flex items-center justify-center p-4 sm:p-8"
            role="dialog"
            aria-modal="true"
            :aria-label="{{ Js::from($title ?: 'Video player') }}"
        >
            <div
                class="absolute inset-0 bg-background/90 backdrop-blur-md"
                @click="closePlayer()"
            ></div>

            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative z-10 w-full max-w-[min(100%,420px)] overflow-hidden rounded-2xl border border-white/10 bg-surface shadow-[0_0_60px_rgba(124,58,237,0.25)]"
            >
                <button
                    type="button"
                    class="absolute right-3 top-3 z-20 flex h-9 w-9 items-center justify-center rounded-full border border-white/15 bg-background/70 text-white backdrop-blur hover:bg-background"
                    @click="closePlayer()"
                    aria-label="Close"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <video
                    class="aspect-[9/16] w-full bg-black object-contain"
                    controls
                    playsinline
                    preload="metadata"
                    @if ($poster) poster="{{ $poster }}" @endif
                    x-ref="player"
                >
                    <source src="{{ $moment->videoUrl() }}" type="video/mp4">
                </video>

                @if ($title)
                    <div class="border-t border-white/10 px-4 py-3">
                        <p class="text-sm font-semibold text-white">{{ $title }}</p>
                    </div>
                @endif
            </div>
        </div>
    </template>
</div>
