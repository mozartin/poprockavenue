@php
    $current = app()->getLocale();
    $labels = supported_locales();
    $currentLabel = $labels[$current] ?? strtoupper($current);
@endphp

<div
    class="relative"
    x-data="{ open: false }"
    @keydown.escape.window="open = false"
    @click.outside="open = false"
>
    <button
        type="button"
        class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-surface/50 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-wider text-white transition-colors hover:border-white/25"
        @click="open = !open"
        :aria-expanded="open"
        aria-haspopup="listbox"
        aria-label="Language"
    >
        <span>{{ $currentLabel }}</span>
        <svg class="h-3 w-3 text-muted transition-transform" :class="open && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="absolute right-0 z-50 mt-2 min-w-[7.5rem] overflow-hidden rounded-xl border border-white/10 bg-surface py-1 shadow-xl shadow-black/40"
        role="listbox"
        aria-label="Languages"
    >
        @foreach ($labels as $code => $label)
            <a
                href="{{ switch_locale_url($code) }}"
                role="option"
                @class([
                    'flex items-center justify-between gap-3 px-3 py-2 text-[11px] font-semibold uppercase tracking-wider transition-colors',
                    'bg-white/10 text-white' => $current === $code,
                    'text-muted hover:bg-white/5 hover:text-white' => $current !== $code,
                ])
                @if ($current === $code) aria-selected="true" @endif
            >
                <span>{{ $label }}</span>
                @if ($current === $code)
                    <span class="h-1.5 w-1.5 rounded-full bg-cyan" aria-hidden="true"></span>
                @endif
            </a>
        @endforeach
    </div>
</div>
