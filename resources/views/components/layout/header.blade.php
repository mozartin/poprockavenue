@php
    use App\Support\SiteMenus;

    $menuItems = SiteMenus::headerItems();

    $links = $menuItems->isNotEmpty()
        ? $menuItems->map(fn ($item) => [
            'label' => $item->label,
            'href' => $item->href(),
            'new_tab' => $item->open_in_new_tab,
        ])
        : collect([
            ['label' => site_t('nav.about'), 'href' => localized_route('about'), 'new_tab' => false],
            ['label' => site_t('nav.events'), 'href' => localized_route('home').'#events', 'new_tab' => false],
            ['label' => site_t('nav.contact'), 'href' => localized_route('contact'), 'new_tab' => false],
        ]);
@endphp

<header
    x-data="{ open: false }"
    class="fixed inset-x-0 top-0 z-50 border-b border-white/5 bg-background/80 backdrop-blur-xl"
>
    <div class="container-site flex h-16 items-center justify-between gap-4 lg:h-20">
        <x-layout.logo />

        <nav class="hidden items-center gap-8 lg:flex" aria-label="Main navigation">
            @foreach ($links as $link)
                <a
                    href="{{ $link['href'] }}"
                    @if ($link['new_tab']) target="_blank" rel="noopener noreferrer" @endif
                    class="text-xs font-medium uppercase tracking-[0.15em] text-muted transition-colors hover:text-white"
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="hidden items-center gap-4 lg:flex">
            <x-layout.language-switcher />
            <a href="{{ localized_route('contact') }}" class="btn-gradient px-5 py-2.5 text-xs">
                {{ site_t('nav.check_availability') }}
            </a>
        </div>

        <button
            type="button"
            class="inline-flex items-center justify-center rounded-md p-2 text-muted hover:text-white lg:hidden"
            @click="open = !open"
            :aria-expanded="open"
            aria-label="{{ site_t('nav.toggle_menu') }}"
        >
            <svg x-show="!open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg x-show="open" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div
        x-show="open"
        x-cloak
        x-transition
        class="border-t border-white/5 bg-background/95 backdrop-blur-xl lg:hidden"
    >
        <nav class="container-site flex flex-col gap-1 py-4" aria-label="Mobile navigation">
            @foreach ($links as $link)
                <a
                    href="{{ $link['href'] }}"
                    @if ($link['new_tab']) target="_blank" rel="noopener noreferrer" @endif
                    class="rounded-md px-3 py-3 text-sm font-medium uppercase tracking-wider text-muted transition-colors hover:bg-white/5 hover:text-white"
                    @click="open = false"
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
            <div class="px-3 py-3">
                <x-layout.language-switcher />
            </div>
            <a href="{{ localized_route('contact') }}" class="btn-gradient mt-3 text-center text-xs" @click="open = false">
                {{ site_t('nav.check_availability') }}
            </a>
        </nav>
    </div>
</header>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
<style>[x-cloak] { display: none !important; }</style>
