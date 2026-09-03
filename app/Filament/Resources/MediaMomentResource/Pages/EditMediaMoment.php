<?php

namespace App\Filament\Resources\MediaMomentResource\Pages;

use App\Filament\Concerns\HandlesTranslatableFormData;
use App\Filament\Resources\MediaMomentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMediaMoment extends EditRecord
{
    use HandlesTranslatableFormData;

    protected static string $resource = MediaMomentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
