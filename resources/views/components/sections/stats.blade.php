@props(['stats' => []])

<section class="border-b border-white/5 py-12 sm:py-16">
    <div class="container-site">
        <div class="grid grid-cols-2 gap-8 lg:grid-cols-4 lg:gap-12">
            @foreach ($stats as $stat)
                <div class="text-center lg:text-left">
                    <p class="text-4xl font-bold text-cyan sm:text-5xl">{{ $stat['value'] }}</p>
                    <p class="mt-2 text-xs font-medium uppercase tracking-[0.15em] text-muted">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
