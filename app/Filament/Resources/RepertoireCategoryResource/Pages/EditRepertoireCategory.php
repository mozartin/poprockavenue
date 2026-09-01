<?php

namespace App\Filament\Resources\RepertoireCategoryResource\Pages;

use App\Filament\Concerns\HandlesTranslatableFormData;
use App\Filament\Resources\RepertoireCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRepertoireCategory extends EditRecord
{
    use HandlesTranslatableFormData;

    protected static string $resource = RepertoireCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
