@php
    use App\Services\SiteSettings;
    $selectedEventType = old('event_type', request('event_type'));
@endphp

<x-layout.app
    :title="site_t('meta.contact_title')"
    :description="site_t('meta.contact_description')"
>
    <x-sections.page-hero
        :eyebrow="site_t('contact.eyebrow')"
        eyebrowColor="text-pink"
        :title="site_t('contact.title')"
        :subtitle="site_t('contact.subtitle')"
    />

    <section class="pb-20 sm:pb-28">
        <div class="container-site">
            <div class="grid gap-12 lg:grid-cols-5">
                <div class="lg:col-span-3">
                    @if (session('success'))
                        <div class="mb-8 rounded-xl border border-cyan/20 bg-cyan/5 p-6 text-cyan" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ localized_route('bookings.store') }}" method="POST" class="space-y-6" novalidate>
                        @csrf

                        <div class="hidden" aria-hidden="true">
                            <label for="website">{{ site_t('contact.honeypot') }}</label>
                            <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="name" class="mb-2 block text-sm font-medium text-white">{{ site_t('contact.name') }} *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full rounded-lg border border-white/10 bg-surface px-4 py-3 text-white focus:border-cyan/50 focus:outline-none focus:ring-1 focus:ring-cyan/50 @error('name') border-pink/50 @enderror">
                                @error('name')<p class="mt-1 text-sm text-pink">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="email" class="mb-2 block text-sm font-medium text-white">{{ site_t('contact.email') }} *</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-white/10 bg-surface px-4 py-3 text-white focus:border-cyan/50 focus:outline-none focus:ring-1 focus:ring-cyan/50 @error('email') border-pink/50 @enderror">
                                @error('email')<p class="mt-1 text-sm text-pink">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="phone" class="mb-2 block text-sm font-medium text-white">{{ site_t('contact.phone') }}</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" class="w-full rounded-lg border border-white/10 bg-surface px-4 py-3 text-white focus:border-cyan/50 focus:outline-none focus:ring-1 focus:ring-cyan/50">
                            </div>
                            <div>
                                <label for="event_type" class="mb-2 block text-sm font-medium text-white">{{ site_t('contact.event_type') }} *</label>
                                <select name="event_type" id="event_type" required class="w-full rounded-lg border border-white/10 bg-surface px-4 py-3 text-white focus:border-cyan/50 focus:outline-none focus:ring-1 focus:ring-cyan/50 @error('event_type') border-pink/50 @enderror">
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
                                <label for="event_date" class="mb-2 block text-sm font-medium text-white">{{ site_t('contact.event_date') }}</label>
                                <input type="date" name="event_date" id="event_date" value="{{ old('event_date') }}" min="{{ date('Y-m-d') }}" class="w-full rounded-lg border border-white/10 bg-surface px-4 py-3 text-white focus:border-cyan/50 focus:outline-none focus:ring-1 focus:ring-cyan/50 @error('event_date') border-pink/50 @enderror">
                                @error('event_date')<p class="mt-1 text-sm text-pink">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="location" class="mb-2 block text-sm font-medium text-white">{{ site_t('contact.location') }}</label>
                                <input type="text" name="location" id="location" value="{{ old('location') }}" placeholder="{{ site_t('contact.location_placeholder') }}" class="w-full rounded-lg border border-white/10 bg-surface px-4 py-3 text-white placeholder:text-muted/50 focus:border-cyan/50 focus:outline-none focus:ring-1 focus:ring-cyan/50">
                            </div>
                        </div>

                        <div>
                            <label for="guests" class="mb-2 block text-sm font-medium text-white">{{ site_t('contact.guests') }}</label>
                            <input type="number" name="guests" id="guests" value="{{ old('guests') }}" min="1" class="w-full rounded-lg border border-white/10 bg-surface px-4 py-3 text-white focus:border-cyan/50 focus:outline-none focus:ring-1 focus:ring-cyan/50 sm:max-w-xs">
                        </div>

                        <div>
                            <label for="message" class="mb-2 block text-sm font-medium text-white">{{ site_t('contact.message') }}</label>
                            <textarea name="message" id="message" rows="5" placeholder="{{ site_t('contact.message_placeholder') }}" class="w-full rounded-lg border border-white/10 bg-surface px-4 py-3 text-white placeholder:text-muted/50 focus:border-cyan/50 focus:outline-none focus:ring-1 focus:ring-cyan/50">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="btn-gradient w-full sm:w-auto">
                            {{ site_t('buttons.check_availability') }}
                        </button>
                    </form>
                </div>

                <aside class="lg:col-span-2">
                    <div class="sticky top-28 space-y-6 rounded-2xl bg-surface p-8">
                        <h2 class="text-lg font-semibold text-white">{{ site_t('contact.aside_title') }}</h2>
                        <div class="space-y-4 text-sm">
                            <div>
                                <p class="text-muted">{{ site_t('contact.email') }}</p>
                                <a href="mailto:{{ SiteSettings::email() }}" class="text-white hover:text-cyan">{{ SiteSettings::email() }}</a>
                            </div>
                            <div>
                                <p class="text-muted">{{ site_t('contact.phone') }}</p>
                                <a href="{{ SiteSettings::phoneLink() }}" class="text-white hover:text-cyan">{{ SiteSettings::phone() }}</a>
                            </div>
                            <div>
                                <p class="text-muted">{{ site_t('contact.location') }}</p>
                                <p class="text-white">{{ SiteSettings::get('location', 'Netherlands') }}</p>
                            </div>
                        </div>
                        <div class="border-t border-white/5 pt-6">
                            <p class="text-sm text-muted">
                                {!! site_t('contact.aside_note', ['hours' => '<strong class="text-white">24</strong>']) !!}
                            </p>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</x-layout.app>
