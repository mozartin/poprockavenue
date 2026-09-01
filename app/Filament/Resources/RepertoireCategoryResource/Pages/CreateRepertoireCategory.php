<?php

namespace App\Filament\Resources\RepertoireCategoryResource\Pages;

use App\Filament\Concerns\HandlesTranslatableFormData;
use App\Filament\Resources\RepertoireCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRepertoireCategory extends CreateRecord
{
    use HandlesTranslatableFormData;

    protected static string $resource = RepertoireCategoryResource::class;
}
