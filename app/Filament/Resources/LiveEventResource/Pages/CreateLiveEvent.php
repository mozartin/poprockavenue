<?php

namespace App\Filament\Resources\LiveEventResource\Pages;

use App\Filament\Concerns\HandlesTranslatableFormData;
use App\Filament\Resources\LiveEventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLiveEvent extends CreateRecord
{
    use HandlesTranslatableFormData;

    protected static string $resource = LiveEventResource::class;
}
