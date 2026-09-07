<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex justify-end">
            <x-filament::button type="submit">
                Save SEO & site basics
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
