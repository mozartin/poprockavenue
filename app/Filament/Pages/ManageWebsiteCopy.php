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

    protected static ?string $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Texts & labels';

    protected static ?string $title = 'Texts & labels';

    protected static ?string $navigationDescription = 'Menu, stats, section titles and other site copy';

    protected static ?int $navigationSort = 2;

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

        foreach ($this->activeToggleKeys() as $activeKey) {
            $formKey = $this->activeFormKey($activeKey);
            SiteSetting::set(
                $activeKey,
                ! empty($state[$formKey]) ? '1' : '0',
                'boolean',
                'stats'
            );
        }

        Cache::forget('site_settings.all');

        Notification::make()
            ->title('Saved')
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

        foreach ($this->activeToggleKeys() as $activeKey) {
            $data[$this->activeFormKey($activeKey)] = (bool) SiteSetting::get($activeKey, true);
        }

        return $data;
    }

    /**
     * @return list<string>
     */
    protected function activeToggleKeys(): array
    {
        $keys = [];

        foreach (config('site_copy.admin_panels', []) as $panel) {
            foreach ($panel['items'] ?? [] as $item) {
                if (! empty($item['active_key'])) {
                    $keys[] = $item['active_key'];
                }
            }
        }

        return array_values(array_unique($keys));
    }

    protected function activeFormKey(string $activeKey): string
    {
        return 'active__'.str_replace('.', '_', $activeKey);
    }

    protected function formSchema(): array
    {
        $panels = config('site_copy.admin_panels', []);
        $handled = [];
        $schema = [];

        foreach ($panels as $panel) {
            $schema[] = $this->buildPanel($panel);
            foreach ($panel['consumes'] ?? [] as $section) {
                $handled[$section] = true;
            }
        }

        foreach (SiteCopy::sections() as $section => $config) {
            if (isset($handled[$section]) || ($config['admin']['hidden'] ?? false)) {
                continue;
            }

            $schema[] = $this->buildDefaultSection($section, $config);
        }

        return $schema;
    }

    /**
     * @param  array<string, mixed>  $panel
     */
    protected function buildPanel(array $panel): Forms\Components\Section
    {
        $items = [];

        foreach ($panel['items'] ?? [] as $item) {
            $items[] = $this->buildItemCard($item);
        }

        return Forms\Components\Section::make($panel['label'] ?? 'Section')
            ->description($panel['description'] ?? null)
            ->schema($items)
            ->collapsed(! ($panel['expanded'] ?? false))
            ->collapsible();
    }

    /**
     * @param  array<string, mixed>  $item
     */
    protected function buildItemCard(array $item): Forms\Components\Section
    {
        $parts = $item['parts'] ?? [];
        $columns = max(1, min(count($parts), 2));
        $schema = [];

        if (! empty($item['active_key'])) {
            $schema[] = Forms\Components\Toggle::make($this->activeFormKey($item['active_key']))
                ->label('Active on homepage')
                ->helperText('Turn off to hide this stat from the homepage.')
                ->default(true)
                ->inline(false);
        }

        $schema[] = TranslatableFields::tabs(function (string $locale) use ($parts, $columns) {
            $fields = [];

            foreach ($parts as $part) {
                $section = $part['section'];
                $field = $part['field'];
                $label = $part['label'] ?? $field;
                $type = $part['type'] ?? 'text';
                $base = "{$section}__{$field}";

                $component = $type === 'textarea'
                    ? TranslatableFields::textarea($base, $label, $locale, 3)->columnSpanFull()
                    : TranslatableFields::text($base, $label, $locale);

                $fields[] = $component;
            }

            return [
                Forms\Components\Grid::make($columns)
                    ->schema($fields),
            ];
        }, 'Translations');

        return Forms\Components\Section::make($item['label'] ?? 'Item')
            ->description($item['description'] ?? null)
            ->schema($schema)
            ->compact();
    }

    /**
     * @param  array<string, mixed>  $config
     */
    protected function buildDefaultSection(string $section, array $config): Forms\Components\Section
    {
        $layout = $config['admin']['layout'] ?? 'fields';

        if ($layout === 'items') {
            $items = [];

            foreach ($config['fields'] as $field => $meta) {
                $items[] = $this->buildItemCard([
                    'label' => $meta['label'] ?? $field,
                    'parts' => [[
                        'section' => $section,
                        'field' => $field,
                        'label' => $meta['input_label'] ?? 'Text',
                        'type' => $meta['type'] ?? 'text',
                    ]],
                ]);
            }

            return Forms\Components\Section::make($config['label'] ?? $section)
                ->description($config['description'] ?? null)
                ->schema($items)
                ->collapsed($section !== 'hero')
                ->collapsible();
        }

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

        return Forms\Components\Section::make($config['label'] ?? $section)
            ->description($config['description'] ?? null)
            ->schema($fields)
            ->collapsed($section !== 'hero')
            ->collapsible();
    }
}
