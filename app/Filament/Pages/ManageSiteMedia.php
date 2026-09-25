<?php

namespace App\Filament\Pages;

use App\Filament\Support\MediaUploads;
use App\Models\SiteSetting;
use App\Services\SiteSettings;
use App\Support\MediaPath;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

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
        // Never hydrate oversized/broken uploads into FilePond — that causes
        // permanent "Waiting for size" / Loading spinners in production.
        $this->form->fill([
            'hero_image' => MediaPath::uploadablePath(SiteSetting::get('hero_image')),
            'live_video_image' => MediaPath::uploadablePath(SiteSetting::get('live_video_image')),
            'cta_background_image' => MediaPath::uploadablePath(SiteSetting::get('cta_background_image')),
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
                            ->helperText('Now live: '.SiteSettings::heroImage()),
                        MediaUploads::image('live_video_image', 'Live experience poster', 'uploads/site')
                            ->helperText('Now live: '.SiteSettings::liveVideoImage()),
                        MediaUploads::image('cta_background_image', 'Booking CTA background', 'uploads/site')
                            ->helperText('Now live: '.SiteSettings::ctaBackgroundImage()),
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
