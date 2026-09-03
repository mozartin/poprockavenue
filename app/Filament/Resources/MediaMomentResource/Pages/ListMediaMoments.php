<?php

namespace App\Filament\Resources\MediaMomentResource\Pages;

use App\Filament\Resources\MediaMomentResource;
use Filament\Resources\Pages\ListRecords;

class ListMediaMoments extends ListRecords
{
    protected static string $resource = MediaMomentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
