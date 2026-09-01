<?php

namespace App\Filament\Concerns;

use App\Filament\Support\TranslatableFields;

trait HandlesTranslatableFormData
{
    protected function translatableAttributes(): array
    {
        return static::getResource()::getTranslatableAttributes();
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();

        foreach ($this->translatableAttributes() as $field) {
            foreach (TranslatableFields::locales() as $locale) {
                $data[TranslatableFields::key($field, $locale)] = $record->getTranslation($field, $locale, false);
            }
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->mergeTranslations($data);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->mergeTranslations($data);
    }

    protected function mergeTranslations(array $data): array
    {
        foreach ($this->translatableAttributes() as $field) {
            $translations = [];

            foreach (TranslatableFields::locales() as $locale) {
                $key = TranslatableFields::key($field, $locale);

                if (array_key_exists($key, $data)) {
                    $translations[$locale] = $data[$key] ?? '';
                    unset($data[$key]);
                }
            }

            if ($translations !== []) {
                $data[$field] = $translations;
            }
        }

        return $data;
    }
}
