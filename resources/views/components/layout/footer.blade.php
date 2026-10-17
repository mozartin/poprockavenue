@php
    use App\Services\SiteSettings;
    use App\Support\SiteMenus;

    $footerSections = SiteMenus::sections('footer');
    $phoneContacts = SiteSettings::phoneContacts();
    $columnCount = 2 + $footerSections->count() + ($phoneContacts !== [] ? 1 : 0); // brand + contact + phones? + menus
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
                <x-layout.social-links />
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
                    <li>{{ SiteSettings::get('location', 'Netherlands') }}</li>
                </ul>
            </div>

            @if ($phoneContacts !== [])
                <div>
                    <h3 class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-white">{{ site_t('footer.phones') }}</h3>
                    <ul class="space-y-3 text-sm text-muted">
                        @foreach ($phoneContacts as $contact)
                            <li>
                                @if ($contact['href'])
                                    <a href="{{ $contact['href'] }}" class="transition-colors hover:text-white">
                                        📞 {{ $contact['label'] }}
                                    </a>
                                @else
                                    <span>📞 {{ $contact['label'] }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
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
