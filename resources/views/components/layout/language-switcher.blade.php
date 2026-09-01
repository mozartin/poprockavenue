<nav class="flex items-center gap-1 rounded-full border border-white/10 bg-surface/50 p-1" aria-label="{{ __('Language') }}">
    @foreach (supported_locales() as $code => $label)
        <a
            href="{{ switch_locale_url($code) }}"
            @class([
                'rounded-full px-3 py-1.5 text-[10px] font-semibold uppercase tracking-wider transition-colors',
                'bg-gradient-to-r from-purple to-cyan text-white' => app()->getLocale() === $code,
                'text-muted hover:text-white' => app()->getLocale() !== $code,
            ])
            @if (app()->getLocale() === $code) aria-current="true" @endif
        >
            {{ $label }}
        </a>
    @endforeach
</nav>
