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

class ManageSeo extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass-circle';

    protected static ?string $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'SEO & site basics';

    protected static ?string $title = 'SEO & site basics';

    protected static ?string $navigationDescription = 'Link preview, default site description, and page SEO titles';

    protected static ?int $navigationSort = 0;

    protected static string $view = 'filament.pages.manage-seo';

    /**
     * @var list<array{0: string, 1: string, 2: string, 3: string}>
     */
    protected array $siteBasicsFields = [
        ['meta', 'default_title', 'Default / fallback title', 'text'],
        ['meta', 'default_description', 'Default description (also used in link previews)', 'textarea'],
        ['meta', 'home_title', 'Homepage browser title', 'text'],
        ['footer', 'description', 'Short site blurb (footer)', 'textarea'],
    ];

    /**
     * @var list<array{0: string, 1: string, 2: string, 3: string}>
     */
    protected array $pageSeoFields = [
        ['meta', 'repertoire_title', 'Repertoire — title', 'text'],
        ['meta', 'repertoire_description', 'Repertoire — description', 'textarea'],
        ['meta', 'media_title', 'Live Moments — title', 'text'],
        ['meta', 'media_description', 'Live Moments — description', 'textarea'],
        ['meta', 'testimonials_title', 'Testimonials — title', 'text'],
        ['meta', 'testimonials_description', 'Testimonials — description', 'textarea'],
        ['meta', 'contact_title', 'Contact — title', 'text'],
        ['meta', 'contact_description', 'Contact — description', 'textarea'],
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
                Forms\Components\Section::make('Link preview (WhatsApp, Telegram, Facebook…)')
                    ->description('Image and texts messengers/social networks show when someone pastes a site link. Recommended image ~1200×630.')
                    ->schema([
                        MediaUploads::image('og_image', 'Share / preview image', 'uploads/site')
                            ->helperText('If empty, the hero image is used as a fallback.'),
                        Forms\Components\TextInput::make('og_site_name')
                            ->label('Site name in previews')
                            ->helperText('Shown as the brand name above the title (og:site_name).')
                            ->maxLength(120),
                        TranslatableFields::tabs(function (string $locale) {
                            return [
                                TranslatableFields::text('meta__default_title', 'Preview / default title', $locale),
                                TranslatableFields::textarea('meta__default_description', 'Preview / default description', $locale, 4)
                                    ->columnSpanFull(),
                            ];
                        }, 'Preview texts'),
                    ]),

                Forms\Components\Section::make('Social links')
                    ->description('Icons in the footer. Leave empty to hide a network.')
                    ->schema([
                        Forms\Components\TextInput::make('instagram_url')->label('Instagram URL')->url()->maxLength(255),
                        Forms\Components\TextInput::make('tiktok_url')->label('TikTok URL')->url()->maxLength(255),
                        Forms\Components\TextInput::make('youtube_url')->label('YouTube URL')->url()->maxLength(255),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Site basics')
                    ->description('Core identity texts used across the site.')
                    ->schema([
                        TranslatableFields::tabs(function (string $locale) {
                            return [
                                TranslatableFields::text('meta__home_title', 'Homepage browser title', $locale),
                                TranslatableFields::textarea('footer__description', 'Short site blurb (footer)', $locale, 3)
                                    ->columnSpanFull(),
                            ];
                        }, 'Basics'),
                    ]),

                Forms\Components\Section::make('Page SEO')
                    ->description('Title and description for each main page. About Us SEO is edited under Website → About Us.')
                    ->schema(
                        collect($this->pageSeoFields)
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

        if (array_key_exists('og_image', $state)) {
            SiteSetting::set('og_image', (string) ($state['og_image'] ?? ''), 'text', 'media');
        }

        SiteSetting::set('og_site_name', (string) ($state['og_site_name'] ?? ''), 'text', 'seo');

        foreach (['instagram_url', 'tiktok_url', 'youtube_url'] as $socialKey) {
            SiteSetting::set($socialKey, (string) ($state[$socialKey] ?? ''), 'text', 'social');
        }

        foreach (array_merge($this->siteBasicsFields, $this->pageSeoFields) as [$section, $field]) {
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
            ->title('SEO & site basics saved')
            ->success()
            ->send();
    }

    protected function loadFormData(): array
    {
        $data = [
            'og_image' => $this->uploadablePath(
                SiteSetting::query()->where('key', 'og_image')->value('value')
            ),
            'og_site_name' => (string) (SiteSetting::query()->where('key', 'og_site_name')->value('value')
                ?: 'POP/ROCK AVENUE'),
            'instagram_url' => (string) (SiteSetting::query()->where('key', 'instagram_url')->value('value') ?? ''),
            'tiktok_url' => (string) (SiteSetting::query()->where('key', 'tiktok_url')->value('value') ?? ''),
            'youtube_url' => (string) (SiteSetting::query()->where('key', 'youtube_url')->value('value') ?? ''),
        ];

        foreach (array_merge($this->siteBasicsFields, $this->pageSeoFields) as [$section, $field]) {
            $base = "{$section}__{$field}";
            $this->fillTranslations(
                $data,
                $base,
                SiteCopy::settingKey($section, $field),
                "site.{$section}.{$field}"
            );
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
