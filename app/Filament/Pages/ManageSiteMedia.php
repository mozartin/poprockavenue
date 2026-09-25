<?php

namespace App\Filament\Pages;

use App\Filament\Support\MediaUploads;
use App\Models\SiteSetting;
use App\Services\SiteSettings;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\HtmlString;

class ManageSiteMedia extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Other images';

    protected static ?string $title = 'Other site images';

    protected static ?string $navigationDescription = 'Hero, live experience, booking CTA and showreel';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.manage-site-media';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'hero_image' => null,
            'live_video_image' => null,
            'cta_background_image' => null,
            'showreel_url' => SiteSetting::get('showreel_url'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Homepage images')
                    ->description('About Us image is edited under Website → About Us. Leave a field empty to keep the current image.')
                    ->schema([
                        MediaUploads::image('hero_image', 'Hero image', 'uploads/site')
                            ->helperText(fn (): HtmlString => $this->currentImageHelper(SiteSettings::heroImage())),
                        MediaUploads::image('live_video_image', 'Live experience poster', 'uploads/site')
                            ->helperText(fn (): HtmlString => $this->currentImageHelper(SiteSettings::liveVideoImage())),
                        MediaUploads::image('cta_background_image', 'Booking CTA background', 'uploads/site')
                            ->helperText(fn (): HtmlString => $this->currentImageHelper(SiteSettings::ctaBackgroundImage())),
                    ])->columns(2),

                Forms\Components\Section::make('Showreel')
                    ->schema([
                        Forms\Components\TextInput::make('showreel_url')
                            ->label('Showreel YouTube / Vimeo URL')
                            ->url()
                            ->maxLength(255),
                    ]),
            ])
            ->statePath('data');
    }

    protected function currentImageHelper(string $url): HtmlString
    {
        return new HtmlString(
            '<span class="block text-sm text-gray-500 dark:text-gray-400">Current — upload a new file to replace. JPEG / WebP, ideally 200–400KB.</span>'.
            '<a href="'.e($url).'" target="_blank" rel="noopener" class="mt-2 inline-block">'.
            '<img src="'.e($url).'" alt="" class="h-24 max-w-full rounded-lg object-cover" loading="lazy" />'.
            '</a>'
        );
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach (['hero_image', 'live_video_image', 'cta_background_image'] as $key) {
            if (filled($state[$key] ?? null)) {
                SiteSetting::set($key, $state[$key], 'text', 'media');
            }
        }

        SiteSetting::set('showreel_url', $state['showreel_url'] ?? '', 'text', 'media');

        Notification::make()
            ->title('Site images saved')
            ->success()
            ->send();
    }
}
