<?php

namespace App\Filament\Pages;

use App\Filament\Support\MediaUploads;
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
use Illuminate\Support\Facades\Storage;

class ManageAboutUs extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'About Us';

    protected static ?string $title = 'About Us — block settings';

    protected static ?string $navigationDescription = 'Homepage About section + /about-us page: image, texts, SEO';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.manage-about-us';

    /**
     * Site setting keys (translatable content body).
     *
     * @var list<string>
     */
    protected array $contentKeys = [
        'about_paragraph_1',
        'about_paragraph_2',
        'about_quote',
    ];

    /**
     * Website copy fields managed here (section.field).
     *
     * @var list<array{0: string, 1: string, 2: string, 3: string}>
     */
    protected array $copyFields = [
        // [section, field, label, type]
        ['about', 'eyebrow', 'Eyebrow', 'text'],
        ['about', 'title', 'Homepage title', 'text'],
        ['about', 'image_alt', 'Image alt text', 'text'],
        ['about', 'page_title', 'About Us page title', 'text'],
        ['about', 'page_subtitle', 'About Us page subtitle', 'textarea'],
        ['about', 'members_empty', 'Members empty state', 'text'],
        ['buttons', 'meet_the_band', '“Meet the band” button', 'text'],
        ['meta', 'band_title', 'Browser / SEO title', 'text'],
        ['meta', 'band_description', 'SEO description', 'textarea'],
    ];

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->loadFormData());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Photo')
                    ->description('Used on the homepage About block and the About Us page hero.')
                    ->schema([
                        MediaUploads::image('about_image', 'About Us image', 'uploads/site'),
                    ]),

                Forms\Components\Section::make('Story texts')
                    ->description('Paragraphs and quote shown on both the homepage and About Us page.')
                    ->schema([
                        TranslatableFields::tabs(function (string $locale) {
                            return [
                                TranslatableFields::textarea('about_paragraph_1', 'Paragraph 1', $locale, 4)->columnSpanFull(),
                                TranslatableFields::textarea('about_paragraph_2', 'Paragraph 2', $locale, 4)->columnSpanFull(),
                                TranslatableFields::textarea('about_quote', 'Quote', $locale, 3)->columnSpanFull(),
                            ];
                        }, 'Story'),
                    ]),

                Forms\Components\Section::make('Section labels')
                    ->description('Headings and labels for the About block / About Us page.')
                    ->schema(
                        collect($this->copyFields)
                            ->map(function (array $field) {
                                [$section, $name, $label, $type] = $field;
                                $base = "{$section}__{$name}";

                                return TranslatableFields::tabs(function (string $locale) use ($base, $label, $type) {
                                    return [
                                        $type === 'textarea'
                                            ? TranslatableFields::textarea($base, $label, $locale, 3)->columnSpanFull()
                                            : TranslatableFields::text($base, $label, $locale),
                                    ];
                                }, $label);
                            })
                            ->all()
                    )
                    ->collapsed()
                    ->collapsible(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        if (filled($state['about_image'] ?? null)) {
            SiteSetting::set('about_image', $state['about_image'], 'text', 'media');
        }

        foreach ($this->contentKeys as $key) {
            SiteSetting::set($key, $this->translationsFromState($state, $key), 'translatable', 'content');
        }

        foreach ($this->copyFields as [$section, $field]) {
            $base = "{$section}__{$field}";
            SiteSetting::set(
                SiteCopy::settingKey($section, $field),
                $this->translationsFromState($state, $base),
                'translatable',
                'copy'
            );
        }

        Cache::forget('site_settings.all');

        Notification::make()
            ->title('About Us block saved')
            ->success()
            ->send();
    }

    protected function loadFormData(): array
    {
        $data = [
            'about_image' => $this->uploadablePath(SiteSetting::get('about_image')),
        ];

        foreach ($this->contentKeys as $key) {
            $this->fillTranslations($data, $key, $key);
        }

        foreach ($this->copyFields as [$section, $field]) {
            $base = "{$section}__{$field}";
            $settingKey = SiteCopy::settingKey($section, $field);
            $this->fillTranslations($data, $base, $settingKey, "site.{$section}.{$field}");
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function fillTranslations(array &$data, string $formBase, string $settingKey, ?string $langKey = null): void
    {
        $setting = SiteSetting::query()->where('key', $settingKey)->first();
        $decoded = $setting ? (json_decode($setting->value ?? '{}', true) ?: []) : [];

        foreach (TranslatableFields::locales() as $locale) {
            $formKey = TranslatableFields::key($formBase, $locale);

            if (isset($decoded[$locale]) && $decoded[$locale] !== '') {
                $data[$formKey] = $decoded[$locale];
                continue;
            }

            $data[$formKey] = $langKey
                ? (string) trans($langKey, [], $locale)
                : '';
        }
    }

    /**
     * @param  array<string, mixed>  $state
     * @return array<string, string>
     */
    protected function translationsFromState(array $state, string $formBase): array
    {
        $translations = [];

        foreach (TranslatableFields::locales() as $locale) {
            $translations[$locale] = (string) ($state[TranslatableFields::key($formBase, $locale)] ?? '');
        }

        return $translations;
    }

    protected function uploadablePath(mixed $path): ?string
    {
        if (! is_string($path) || $path === '') {
            return null;
        }

        $path = ltrim($path, '/');

        if (
            str_starts_with($path, 'uploads/')
            || str_starts_with($path, 'media/')
            || Storage::disk('public')->exists($path)
        ) {
            return $path;
        }

        return null;
    }
}
