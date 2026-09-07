<?php

namespace App\Filament\Resources\MenuSectionResource\Pages;

use App\Filament\Concerns\HandlesTranslatableFormData;
use App\Filament\Resources\MenuSectionResource;
use App\Support\SiteMenus;
use Filament\Resources\Pages\CreateRecord;

class CreateMenuSection extends CreateRecord
{
    use HandlesTranslatableFormData;

    protected static string $resource = MenuSectionResource::class;

    protected function afterCreate(): void
    {
        SiteMenus::forgetCache();
    }
}
