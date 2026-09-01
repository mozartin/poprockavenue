@props(['items' => []])

<section class="overflow-hidden border-y border-white/5 bg-surface/50 py-3">
    <div class="flex animate-marquee whitespace-nowrap">
        @foreach (range(1, 2) as $loop)
            <div class="flex shrink-0 items-center gap-8 px-4">
                @foreach ($items as $item)
                    <span class="text-xs font-medium uppercase tracking-[0.2em] text-muted">{{ $item }}</span>
                    <span class="text-cyan">✦</span>
                @endforeach
            </div>
        @endforeach
    </div>
</section>
