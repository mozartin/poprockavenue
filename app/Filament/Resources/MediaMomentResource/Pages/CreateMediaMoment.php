<?php

namespace App\Filament\Resources\MediaMomentResource\Pages;

use App\Filament\Concerns\HandlesTranslatableFormData;
use App\Filament\Resources\MediaMomentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMediaMoment extends CreateRecord
{
    use HandlesTranslatableFormData;

    protected static string $resource = MediaMomentResource::class;
}
