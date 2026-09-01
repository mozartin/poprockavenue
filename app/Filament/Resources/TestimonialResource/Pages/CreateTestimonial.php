<?php

namespace App\Filament\Resources\TestimonialResource\Pages;

use App\Filament\Concerns\HandlesTranslatableFormData;
use App\Filament\Resources\TestimonialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTestimonial extends CreateRecord
{
    use HandlesTranslatableFormData;

    protected static string $resource = TestimonialResource::class;
}
