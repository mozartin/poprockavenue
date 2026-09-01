<x-layout.app
    :title="__('site.meta.repertoire_title')"
    :description="__('site.meta.repertoire_description')"
>
    <x-sections.page-hero
        :eyebrow="__('site.repertoire_section.eyebrow')"
        eyebrowColor="text-pink"
        :title="__('site.repertoire_section.title')"
        :subtitle="__('site.repertoire_section.subtitle')"
    />

    <section class="pb-20 sm:pb-28">
        <div class="container-site">
            @foreach ($categories as $index => $category)
                <div id="{{ $category->slug }}" class="scroll-mt-28">
                    <div class="mb-6 flex items-center gap-4">
                        <span class="text-sm font-medium" style="color: {{ $category->accent_color }}80">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <h2 class="text-3xl font-bold" style="color: {{ $category->accent_color }}">
                            {{ $category->name }}
                        </h2>
                    </div>

                    <div class="mb-16 flex flex-wrap gap-2">
                        @foreach ($category->songs as $song)
                            <span class="rounded-full bg-surface px-4 py-2 text-sm text-muted">
                                {{ $song->displayName() }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="rounded-2xl border border-white/5 bg-surface/50 p-8 text-center sm:p-12">
                <h2 class="text-2xl font-bold text-white">{{ __('site.repertoire_section.custom_title') }}</h2>
                <p class="mx-auto mt-4 max-w-xl text-muted">
                    {{ __('site.repertoire_section.custom_text') }}
                </p>
                <div class="mt-8">
                    <x-ui.button href="{{ localized_route('contact') }}">{{ __('site.buttons.request_custom_setlist') }}</x-ui.button>
                </div>
            </div>
        </div>
    </section>
</x-layout.app>
