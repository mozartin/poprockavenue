@props(['stats' => []])

@php
    $count = count($stats);
    $gridCols = match (true) {
        $count <= 1 => 'grid-cols-1',
        $count === 2 => 'grid-cols-2',
        $count === 3 => 'grid-cols-2 lg:grid-cols-3',
        default => 'grid-cols-2 lg:grid-cols-4',
    };
@endphp

<section class="border-b border-white/5 py-12 sm:py-16">
    <div class="container-site">
        <div class="grid gap-8 lg:gap-12 {{ $gridCols }}">
            @foreach ($stats as $stat)
                <div class="text-center lg:text-left">
                    <p class="text-4xl font-bold text-cyan sm:text-5xl">{{ $stat['value'] }}</p>
                    <p class="mt-2 text-xs font-medium uppercase tracking-[0.15em] text-muted">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
