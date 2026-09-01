<?php

namespace App\Filament\Support;

use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;

class TranslatableFields
{
    public static function locales(): array
    {
        return array_keys(config('app.supported_locales', ['en' => 'EN']));
    }

    /**
     * @param  callable(string $locale): array<int, Forms\Components\Component>  $fields
     */
    public static function tabs(callable $fields, string $label = 'Translations'): Tabs
    {
        return Tabs::make($label)
            ->tabs(
                collect(config('app.supported_locales', []))
                    ->map(fn (string $localeLabel, string $locale) => Tab::make($localeLabel)
                        ->schema($fields($locale)))
                    ->values()
                        ->all()
            )
            ->columnSpanFull();
    }

    public static function text(string $field, string $label, string $locale, bool $required = false): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make(self::key($field, $locale))
            ->label($label)
            ->required($required && $locale === 'en')
            ->maxLength(255);
    }

    public static function textarea(string $field, string $label, string $locale, int $rows = 3): Forms\Components\Textarea
    {
        return Forms\Components\Textarea::make(self::key($field, $locale))
            ->label($label)
            ->rows($rows);
    }

    public static function richEditor(string $field, string $label, string $locale): Forms\Components\RichEditor
    {
        return Forms\Components\RichEditor::make(self::key($field, $locale))
            ->label($label)
            ->columnSpanFull();
    }

    public static function key(string $field, string $locale): string
    {
        return "{$field}_{$locale}";
    }
}
