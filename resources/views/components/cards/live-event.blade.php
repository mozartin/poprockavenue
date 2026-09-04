@props(['event'])

@php
    /** @var \App\Models\LiveEvent $event */
    $poster = $event->posterUrl();
    $locale = app()->getLocale();
    $date = $event->starts_at->locale($locale)->translatedFormat('d M Y');
    $time = $event->starts_at->format('H:i');
@endphp

<article class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/[0.03]">
    <div class="grid lg:grid-cols-[minmax(0,1.1fr)_minmax(0,1fr)]">
        <div class="relative aspect-[4/5] overflow-hidden sm:aspect-[16/11] lg:aspect-auto lg:min-h-[420px]">
            @if ($poster)
                <img
                    src="{{ $poster }}"
                    alt="{{ $event->title }}"
                    class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                    loading="lazy"
                />
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-cyan/30 via-background to-purple/40"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-background via-background/20 to-transparent lg:bg-gradient-to-r lg:from-transparent lg:via-background/40 lg:to-background"></div>
        </div>

        <div class="relative flex flex-col justify-center gap-5 p-6 sm:p-8 lg:p-10">
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs font-semibold uppercase tracking-[0.18em] text-cyan">
                <time datetime="{{ $event->starts_at->toIso8601String() }}">{{ $date }} · {{ $time }}</time>
                @if ($event->city)
                    <span class="text-muted">{{ $event->city }}</span>
                @endif
            </div>

            <div>
                <h3 class="text-2xl font-bold text-white sm:text-3xl">{{ $event->title }}</h3>
                <p class="mt-2 text-sm font-medium text-white/80">
                    {{ $event->venue_name }}
                    @if ($event->venue_address)
                        <span class="text-muted"> · {{ $event->venue_address }}</span>
                    @endif
                </p>
            </div>

            @if ($event->description)
                <p class="max-w-xl text-sm leading-relaxed text-muted sm:text-base">{{ $event->description }}</p>
            @endif

            <div class="flex flex-wrap items-center gap-4 pt-1">
                @if ($event->ticket_info)
                    @if (filled($event->ticket_url))
                        <a
                            href="{{ $event->ticket_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="rounded-full border border-white/15 px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-white transition-colors hover:border-cyan/50 hover:text-cyan"
                        >
                            {{ $event->ticket_info }}
                        </a>
                    @else
                        <span class="rounded-full border border-white/15 px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-white">
                            {{ $event->ticket_info }}
                        </span>
                    @endif
                @endif

                @if ($event->info_url)
                    <a
                        href="{{ $event->info_url }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn-gradient px-5 py-2.5 text-xs"
                    >
                        {{ site_t('events_section.cta') }}
                    </a>
                @elseif (filled($event->ticket_url) && ! $event->ticket_info)
                    <a
                        href="{{ $event->ticket_url }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn-gradient px-5 py-2.5 text-xs"
                    >
                        {{ site_t('events_section.cta') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</article>
