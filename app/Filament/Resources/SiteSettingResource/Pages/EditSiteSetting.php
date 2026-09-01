<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Concerns\HandlesTranslatableSiteSetting;
use App\Filament\Resources\SiteSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSiteSetting extends EditRecord
{
    use HandlesTranslatableSiteSetting;

    protected static string $resource = SiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
