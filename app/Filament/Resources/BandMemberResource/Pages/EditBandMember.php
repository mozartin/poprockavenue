<?php

namespace App\Filament\Resources\BandMemberResource\Pages;

use App\Filament\Concerns\HandlesTranslatableFormData;
use App\Filament\Resources\BandMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBandMember extends EditRecord
{
    use HandlesTranslatableFormData;

    protected static string $resource = BandMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
