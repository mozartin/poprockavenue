<section id="repertoire" class="py-20 sm:py-28">
    <div class="container-site space-y-12">
        <x-ui.section-header
            :eyebrow="site_t('repertoire_section.eyebrow')"
            eyebrowColor="text-pink"
            :title="site_t('repertoire_section.title')"
            :subtitle="site_t('repertoire_section.subtitle')"
        />

        <div>
            @foreach ($repertoireCategories as $index => $category)
                <x-cards.repertoire-row :category="$category" :index="$index + 1" />
            @endforeach
        </div>

        <div class="pt-4 text-center">
            <x-ui.button href="{{ localized_route('repertoire') }}" variant="outline">
                {{ site_t('buttons.view_full_repertoire') }}
            </x-ui.button>
        </div>
    </div>
</section>
