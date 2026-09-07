<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex items-center justify-between gap-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Band member cards are edited under
                <a href="{{ \App\Filament\Resources\BandMemberResource::getUrl() }}" class="text-primary-600 underline">
                    Content → Band Members
                </a>.
            </p>
            <x-filament::button type="submit">
                Save About Us
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
