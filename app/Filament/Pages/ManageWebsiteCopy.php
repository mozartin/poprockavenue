<?php

namespace App\Filament\Pages;

use App\Filament\Support\TranslatableFields;
use App\Models\SiteSetting;
use App\Support\SiteCopy;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class ManageWebsiteCopy extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Website Texts';

    protected static ?string $title = 'Website Texts';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.manage-website-copy';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->loadFormData());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->formSchema())
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach (SiteCopy::sections() as $section => $config) {
            foreach (array_keys($config['fields'] ?? []) as $field) {
                $translations = [];

                foreach (TranslatableFields::locales() as $locale) {
                    $formKey = TranslatableFields::key("{$section}__{$field}", $locale);
                    $translations[$locale] = (string) ($state[$formKey] ?? '');
                }

                SiteSetting::set(
                    SiteCopy::settingKey($section, $field),
                    $translations,
                    'translatable',
                    'copy'
                );
            }
        }

        Cache::forget('site_settings.all');

        Notification::make()
            ->title('Website texts saved')
            ->success()
            ->send();
    }

    protected function loadFormData(): array
    {
        $data = [];
        $locales = TranslatableFields::locales();

        foreach (SiteCopy::sections() as $section => $config) {
            foreach (array_keys($config['fields'] ?? []) as $field) {
                $setting = SiteSetting::query()
                    ->where('key', SiteCopy::settingKey($section, $field))
                    ->first();

                $decoded = [];

                if ($setting) {
                    $decoded = json_decode($setting->value ?? '{}', true) ?: [];
                }

                foreach ($locales as $locale) {
                    $formKey = TranslatableFields::key("{$section}__{$field}", $locale);

                    if (isset($decoded[$locale]) && $decoded[$locale] !== '') {
                        $data[$formKey] = $decoded[$locale];
                        continue;
                    }

                    if ($section === 'stats_values') {
                        $data[$formKey] = config("site_copy.stats_value_defaults.{$field}", '');
                        continue;
                    }

                    $data[$formKey] = (string) trans("site.{$section}.{$field}", [], $locale);
                }
            }
        }

        return $data;
    }

    protected function formSchema(): array
    {
        $sections = [];

        foreach (SiteCopy::sections() as $section => $config) {
            $fields = [];

            foreach ($config['fields'] as $field => $meta) {
                $label = $meta['label'] ?? $field;
                $type = $meta['type'] ?? 'text';
                $base = "{$section}__{$field}";

                $fields[] = TranslatableFields::tabs(function (string $locale) use ($base, $label, $type) {
                    return [
                        $type === 'textarea'
                            ? TranslatableFields::textarea($base, $label, $locale, 3)->columnSpanFull()
                            : TranslatableFields::text($base, $label, $locale),
                    ];
                }, $label);
            }

            $sections[] = Forms\Components\Section::make($config['label'] ?? $section)
                ->schema($fields)
                ->collapsed($section !== 'hero')
                ->collapsible();
        }

        return $sections;
    }
}
