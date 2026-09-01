<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Concerns\HandlesTranslatableSiteSetting;
use App\Filament\Resources\SiteSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSiteSetting extends CreateRecord
{
    use HandlesTranslatableSiteSetting;

    protected static string $resource = SiteSettingResource::class;
}
