@php
    use App\Services\SiteSettings;
    use App\Support\SiteMenus;

    $footerSections = SiteMenus::sections('footer');
    $columnCount = 2 + $footerSections->count(); // brand + contact + menu sections
@endphp

<footer class="border-t border-white/5 bg-background pt-16 pb-8">
    <div class="container-site">
        <div @class([
            'grid gap-12 md:grid-cols-2',
            'lg:grid-cols-3' => $columnCount <= 3,
            'lg:grid-cols-4' => $columnCount === 4,
            'lg:grid-cols-5' => $columnCount === 5,
            'lg:grid-cols-3 xl:grid-cols-6' => $columnCount > 5,
        ])>
            <div class="space-y-5">
                <x-layout.logo />
                <p class="max-w-xs text-sm leading-relaxed text-muted">
                    {{ site_t('footer.description') }}
                </p>
                <div class="flex items-center gap-3">
                    @if (SiteSettings::instagram())
                        <a
                            href="{{ SiteSettings::instagram() }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group flex h-12 w-12 items-center justify-center rounded-full border border-white/15 bg-white/5 text-white transition-all duration-300 hover:-translate-y-0.5 hover:border-transparent hover:bg-gradient-to-br hover:from-purple hover:to-cyan hover:shadow-[0_0_24px_rgba(124,58,237,0.55)]"
                            aria-label="Instagram"
                        >
                            <svg class="h-5 w-5 transition-transform duration-300 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                    @endif
                    @if (SiteSettings::tiktok())
                        <a
                            href="{{ SiteSettings::tiktok() }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group flex h-12 w-12 items-center justify-center rounded-full border border-white/15 bg-white/5 text-white transition-all duration-300 hover:-translate-y-0.5 hover:border-transparent hover:bg-gradient-to-br hover:from-purple hover:to-cyan hover:shadow-[0_0_24px_rgba(34,211,238,0.5)]"
                            aria-label="TikTok"
                        >
                            <svg class="h-5 w-5 transition-transform duration-300 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1v-3.5a6.37 6.37 0 0 0-.79-.05A6.34 6.34 0 0 0 3.15 15.8a6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.34-6.34V8.66a8.2 8.2 0 0 0 4.76 1.52V6.74a4.85 4.85 0 0 1-1-.05z"/></svg>
                        </a>
                    @endif
                    @if (SiteSettings::youtube())
                        <a
                            href="{{ SiteSettings::youtube() }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group flex h-12 w-12 items-center justify-center rounded-full border border-white/15 bg-white/5 text-white transition-all duration-300 hover:-translate-y-0.5 hover:border-transparent hover:bg-gradient-to-br hover:from-purple hover:to-cyan hover:shadow-[0_0_24px_rgba(244,63,94,0.45)]"
                            aria-label="YouTube"
                        >
                            <svg class="h-5 w-5 transition-transform duration-300 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    @endif
                </div>
            </div>

            @foreach ($footerSections as $section)
                <div>
                    @if ($section->show_title && filled($section->title))
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-white">{{ $section->title }}</h3>
                    @endif
                    <ul class="space-y-3 text-sm text-muted">
                        @foreach ($section->items as $item)
                            <li>
                                <a
                                    href="{{ $item->href() }}"
                                    @if ($item->open_in_new_tab) target="_blank" rel="noopener noreferrer" @endif
                                    class="transition-colors hover:text-white"
                                >
                                    {{ $item->label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <div>
                <h3 class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-white">{{ site_t('footer.contact') }}</h3>
                <ul class="space-y-3 text-sm text-muted">
                    <li><a href="mailto:{{ SiteSettings::email() }}" class="transition-colors hover:text-white">{{ SiteSettings::email() }}</a></li>
                    <li><a href="{{ SiteSettings::phoneLink() }}" class="transition-colors hover:text-white">{{ SiteSettings::phone() }}</a></li>
                    <li>{{ SiteSettings::get('location', 'Netherlands') }}</li>
                </ul>
            </div>
        </div>

        <div class="mt-12 flex flex-col gap-4 border-t border-white/5 pt-8 text-xs text-muted sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <img
                    src="{{ asset('images/logo/logo-mark-v1-transparent.png') }}"
                    alt=""
                    width="24"
                    height="23"
                    class="h-6 w-6 object-contain opacity-60"
                    decoding="async"
                    aria-hidden="true"
                >
                <p>&copy; {{ date('Y') }} POP/ROCK AVENUE. {{ site_t('footer.rights') }}</p>
            </div>
        </div>
    </div>
</footer>
