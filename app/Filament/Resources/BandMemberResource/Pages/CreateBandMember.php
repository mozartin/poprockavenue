<?php

namespace App\Filament\Resources\BandMemberResource\Pages;

use App\Filament\Concerns\HandlesTranslatableFormData;
use App\Filament\Resources\BandMemberResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBandMember extends CreateRecord
{
    use HandlesTranslatableFormData;

    protected static string $resource = BandMemberResource::class;
}
