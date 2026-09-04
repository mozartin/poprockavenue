<?php

namespace App\Filament\Resources\LiveEventResource\Pages;

use App\Filament\Concerns\HandlesTranslatableFormData;
use App\Filament\Resources\LiveEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLiveEvent extends EditRecord
{
    use HandlesTranslatableFormData;

    protected static string $resource = LiveEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
