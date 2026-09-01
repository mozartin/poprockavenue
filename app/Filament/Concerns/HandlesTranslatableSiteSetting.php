<?php

namespace App\Filament\Concerns;

use App\Filament\Support\TranslatableFields;

trait HandlesTranslatableSiteSetting
{
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (($data['type'] ?? null) !== 'translatable') {
            return $data;
        }

        $decoded = json_decode($data['value'] ?? '{}', true);

        if (! is_array($decoded)) {
            return $data;
        }

        foreach (TranslatableFields::locales() as $locale) {
            $data[TranslatableFields::key('value', $locale)] = $decoded[$locale] ?? '';
        }

        unset($data['value']);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->encodeTranslatableValue($data);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->encodeTranslatableValue($data);
    }

    protected function encodeTranslatableValue(array $data): array
    {
        if (($data['type'] ?? null) !== 'translatable') {
            return $data;
        }

        $translations = [];

        foreach (TranslatableFields::locales() as $locale) {
            $key = TranslatableFields::key('value', $locale);
            $translations[$locale] = $data[$key] ?? '';
            unset($data[$key]);
        }

        $data['value'] = json_encode($translations);

        return $data;
    }
}
