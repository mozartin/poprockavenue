<section id="services" class="py-20 sm:py-28">
    <div class="container-site space-y-12">
        <x-ui.section-header
            :eyebrow="site_t('services_section.eyebrow')"
            eyebrowColor="text-cyan"
            :title="site_t('services_section.title')"
        />

        @if ($eventTypes->isEmpty())
            <p class="text-center text-sm text-muted">{{ site_t('services_section.empty') }}</p>
        @else
            <div
                class="relative"
                x-data="{
                    index: 0,
                    count: {{ $eventTypes->count() }},
                    timer: null,
                    paused: false,
                    init() {
                        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                            return;
                        }
                        this.startAutoplay();
                    },
                    destroy() {
                        this.stopAutoplay();
                    },
                    startAutoplay() {
                        this.stopAutoplay();
                        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                            return;
                        }
                        this.timer = setInterval(() => {
                            if (this.paused || document.hidden) {
                                return;
                            }
                            this.scrollTo(this.index >= this.count - 1 ? 0 : this.index + 1);
                        }, 3500);
                    },
                    stopAutoplay() {
                        if (this.timer) {
                            clearInterval(this.timer);
                            this.timer = null;
                        }
                    },
                    updateIndex() {
                        const track = this.$refs.track;
                        if (! track) return;
                        const slide = track.querySelector('[data-slide]');
                        if (! slide) return;
                        const slideWidth = slide.getBoundingClientRect().width;
                        const gap = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap) || 0;
                        this.index = Math.min(
                            this.count - 1,
                            Math.max(0, Math.round(track.scrollLeft / (slideWidth + gap)))
                        );
                    },
                    scrollTo(i) {
                        const track = this.$refs.track;
                        const slide = track?.querySelectorAll('[data-slide]')[i];
                        if (! track || ! slide) return;
                        track.scrollTo({ left: slide.offsetLeft - track.offsetLeft, behavior: 'smooth' });
                        this.index = i;
                        this.startAutoplay();
                    },
                    prev() {
                        this.scrollTo(this.index <= 0 ? this.count - 1 : this.index - 1);
                    },
                    next() {
                        this.scrollTo(this.index >= this.count - 1 ? 0 : this.index + 1);
                    },
                }"
                @mouseenter="paused = true"
                @mouseleave="paused = false"
                @focusin="paused = true"
                @focusout="paused = false"
            >
                <div class="mb-6 flex items-center justify-end gap-3">
                    <button
                        type="button"
                        class="flex h-11 w-11 items-center justify-center rounded-full border border-white/15 bg-white/5 text-white transition-colors hover:border-white/30 hover:bg-white/10"
                        @click="prev()"
                        aria-label="Previous"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        class="flex h-11 w-11 items-center justify-center rounded-full border border-white/15 bg-white/5 text-white transition-colors hover:border-white/30 hover:bg-white/10"
                        @click="next()"
                        aria-label="Next"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </div>

                <div
                    x-ref="track"
                    class="flex snap-x snap-mandatory gap-4 overflow-x-auto scroll-smooth pb-2 pr-[12%] [-ms-overflow-style:none] [scrollbar-width:none] sm:pr-[28%] lg:gap-6 lg:pr-[32%] [&::-webkit-scrollbar]:hidden"
                    @scroll.debounce.50ms="updateIndex()"
                >
                    @foreach ($eventTypes as $event)
                        <div
                            data-slide
                            class="w-[88%] shrink-0 snap-start sm:w-[72%] lg:w-[68%]"
                        >
                            <x-cards.event-type :event="$event" size="slider" />
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex items-center justify-center gap-2" role="tablist" aria-label="Services">
                    @foreach ($eventTypes as $i => $event)
                        <button
                            type="button"
                            class="h-2 rounded-full transition-all duration-300"
                            :class="index === {{ $i }} ? 'w-8 bg-pink' : 'w-2 bg-white/25 hover:bg-white/40'"
                            @click="scrollTo({{ $i }})"
                            :aria-selected="index === {{ $i }}"
                            aria-label="{{ $event->title }}"
                        ></button>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
