<?php

namespace App\Filament\Resources\RepertoireCategoryResource\Pages;

use App\Filament\Resources\RepertoireCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRepertoireCategories extends ListRecords
{
    protected static string $resource = RepertoireCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
