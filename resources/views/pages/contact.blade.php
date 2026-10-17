@php
    use App\Services\SiteSettings;
    $selectedEventType = old('event_type', request('event_type'));
@endphp

<x-layout.app
    :title="site_t('meta.contact_title')"
    :description="site_t('meta.contact_description')"
>
    <div class="relative">
        <div
            class="pointer-events-none absolute inset-x-0 top-0 h-[32rem] bg-[radial-gradient(ellipse_at_50%_-10%,rgba(167,139,250,0.22),transparent_55%),radial-gradient(ellipse_at_85%_20%,rgba(34,211,238,0.1),transparent_40%),linear-gradient(180deg,rgba(255,255,255,0.04)_0%,transparent_55%)]"
            aria-hidden="true"
        ></div>

        <div class="relative">
            <x-sections.page-hero
                :eyebrow="site_t('contact.eyebrow')"
                eyebrowColor="text-pink"
                :title="site_t('contact.title')"
                :subtitle="site_t('contact.subtitle')"
            />

            <section class="pb-20 sm:pb-28">
                <div class="container-site">
                    <div class="grid gap-8 lg:grid-cols-5 lg:gap-12">
                        <div class="lg:col-span-3">
                            @if (session('success'))
                                <div class="mb-8 rounded-xl border border-cyan/20 bg-cyan/10 p-6 text-cyan" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form
                                id="booking-form"
                                action="{{ localized_route('bookings.store') }}"
                                method="POST"
                                class="scroll-mt-28 space-y-6 rounded-2xl border border-white/10 bg-surface-raised p-6 sm:p-8"
                                novalidate
                                x-data
                                x-init="$nextTick(() => { document.getElementById('name')?.focus() })"
                            >
                                @csrf

                                <div class="hidden" aria-hidden="true">
                                    <label for="website">{{ site_t('contact.honeypot') }}</label>
                                    <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                                </div>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="name" class="mb-2 block text-sm font-medium text-white/90">{{ site_t('contact.name') }} *</label>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus class="form-field @error('name') border-pink/50 @enderror">
                                        @error('name')<p class="mt-1 text-sm text-pink">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="email" class="mb-2 block text-sm font-medium text-white/90">{{ site_t('contact.email') }} *</label>
                                        <input type="email" name="email" id="email" value="{{ old('email') }}" required class="form-field @error('email') border-pink/50 @enderror">
                                        @error('email')<p class="mt-1 text-sm text-pink">{{ $message }}</p>@enderror
                                    </div>
                                </div>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="phone" class="mb-2 block text-sm font-medium text-white/90">{{ site_t('contact.phone') }}</label>
                                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" class="form-field">
                                    </div>
                                    <div>
                                        <label for="event_type" class="mb-2 block text-sm font-medium text-white/90">{{ site_t('contact.event_type') }} *</label>
                                        <select name="event_type" id="event_type" required class="form-field @error('event_type') border-pink/50 @enderror">
                                            <option value="" disabled {{ ! $selectedEventType ? 'selected' : '' }}>{{ site_t('contact.select_event_type') }}</option>
                                            @foreach ($eventTypes as $type)
                                                <option value="{{ $type->name }}" @selected($selectedEventType === $type->name)>{{ $type->name }}</option>
                                            @endforeach
                                            <option value="{{ site_t('contact.other') }}" @selected($selectedEventType === site_t('contact.other'))>{{ site_t('contact.other') }}</option>
                                        </select>
                                        @error('event_type')<p class="mt-1 text-sm text-pink">{{ $message }}</p>@enderror
                                    </div>
                                </div>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="event_date" class="mb-2 block text-sm font-medium text-white/90">{{ site_t('contact.event_date') }}</label>
                                        <input type="date" name="event_date" id="event_date" value="{{ old('event_date') }}" min="{{ date('Y-m-d') }}" class="form-field @error('event_date') border-pink/50 @enderror">
                                        @error('event_date')<p class="mt-1 text-sm text-pink">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="location" class="mb-2 block text-sm font-medium text-white/90">{{ site_t('contact.location') }}</label>
                                        <input type="text" name="location" id="location" value="{{ old('location') }}" placeholder="{{ site_t('contact.location_placeholder') }}" class="form-field">
                                    </div>
                                </div>

                                <div>
                                    <label for="guests" class="mb-2 block text-sm font-medium text-white/90">{{ site_t('contact.guests') }}</label>
                                    <input type="number" name="guests" id="guests" value="{{ old('guests') }}" min="1" class="form-field sm:max-w-xs">
                                </div>

                                <div>
                                    <label for="message" class="mb-2 block text-sm font-medium text-white/90">{{ site_t('contact.message') }}</label>
                                    <textarea name="message" id="message" rows="5" placeholder="{{ site_t('contact.message_placeholder') }}" class="form-field resize-y">{{ old('message') }}</textarea>
                                </div>

                                <button type="submit" class="btn-gradient w-full sm:w-auto">
                                    {{ site_t('buttons.check_availability') }}
                                </button>
                            </form>
                        </div>

                        <aside class="lg:col-span-2">
                            <div class="sticky top-28 rounded-2xl border border-white/10 bg-surface-raised p-8">
                                <div class="mb-6 h-0.5 w-12 rounded-full bg-gradient-to-r from-purple to-cyan" aria-hidden="true"></div>

                                <h2 class="text-lg font-semibold text-white">{{ site_t('contact.aside_title') }}</h2>

                                <div class="mt-6 space-y-5 text-sm">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.14em] text-muted">{{ site_t('contact.email') }}</p>
                                        <a href="mailto:{{ SiteSettings::email() }}" class="mt-1.5 block text-white transition-colors hover:text-cyan">{{ SiteSettings::email() }}</a>
                                    </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.14em] text-muted">{{ site_t('contact.phone') }}</p>
                                <div class="mt-1.5 space-y-2">
                                    @forelse (SiteSettings::phoneContacts() as $contact)
                                        @if ($contact['href'])
                                            <a href="{{ $contact['href'] }}" class="block text-white transition-colors hover:text-cyan">
                                                📞 {{ $contact['label'] }}
                                            </a>
                                        @else
                                            <p class="text-white">📞 {{ $contact['label'] }}</p>
                                        @endif
                                    @empty
                                        <a href="{{ SiteSettings::phoneLink() }}" class="block text-white transition-colors hover:text-cyan">
                                            📞 {{ SiteSettings::phone() }}
                                        </a>
                                    @endforelse
                                </div>
                            </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.14em] text-muted">{{ site_t('contact.location') }}</p>
                                        <p class="mt-1.5 text-white">{{ SiteSettings::get('location', 'Netherlands') }}</p>
                                    </div>
                                </div>

                                @if (SiteSettings::instagram() || SiteSettings::tiktok() || SiteSettings::youtube())
                                    <div class="mt-6 border-t border-white/10 pt-6">
                                        <p class="mb-4 text-xs uppercase tracking-[0.14em] text-muted">{{ site_t('contact.social') }}</p>
                                        <x-layout.social-links size="sm" />
                                    </div>
                                @endif

                                <div class="mt-6 border-t border-white/10 pt-6">
                                    <p class="text-sm leading-relaxed text-muted">
                                        {!! site_t('contact.aside_note', ['hours' => '<strong class="text-white">24</strong>']) !!}
                                    </p>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-layout.app>
