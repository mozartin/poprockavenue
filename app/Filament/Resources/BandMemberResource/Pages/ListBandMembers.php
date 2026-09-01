<?php

namespace App\Filament\Resources\BandMemberResource\Pages;

use App\Filament\Resources\BandMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBandMembers extends ListRecords
{
    protected static string $resource = BandMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
