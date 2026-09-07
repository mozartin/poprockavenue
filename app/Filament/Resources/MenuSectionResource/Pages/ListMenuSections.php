<?php

namespace App\Filament\Resources\MenuSectionResource\Pages;

use App\Filament\Resources\MenuSectionResource;
use App\Support\SiteMenus;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMenuSections extends ListRecords
{
    protected static string $resource = MenuSectionResource::class;

    public function mount(): void
    {
        SiteMenus::ensureReady();

        parent::mount();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
