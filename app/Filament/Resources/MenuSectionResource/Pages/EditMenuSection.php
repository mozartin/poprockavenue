<?php

namespace App\Filament\Resources\MenuSectionResource\Pages;

use App\Filament\Concerns\HandlesTranslatableFormData;
use App\Filament\Resources\MenuSectionResource;
use App\Support\SiteMenus;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMenuSection extends EditRecord
{
    use HandlesTranslatableFormData;

    protected static string $resource = MenuSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(fn () => SiteMenus::forgetCache()),
        ];
    }

    protected function afterSave(): void
    {
        SiteMenus::forgetCache();
    }
}
