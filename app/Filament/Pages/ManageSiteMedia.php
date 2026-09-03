<?php

namespace App\Filament\Pages;

use App\Filament\Support\MediaUploads;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;

class ManageSiteMedia extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Site Images';

    protected static ?string $title = 'Site Images';

    protected static ?int $navigationSort = 0;

    protected static string $view = 'filament.pages.manage-site-media';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'hero_image' => $this->uploadablePath(SiteSetting::get('hero_image')),
            'about_image' => $this->uploadablePath(SiteSetting::get('about_image')),
            'live_video_image' => $this->uploadablePath(SiteSetting::get('live_video_image')),
            'cta_background_image' => $this->uploadablePath(SiteSetting::get('cta_background_image')),
            'showreel_url' => SiteSetting::get('showreel_url'),
        ]);
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Homepage images')
                    ->description('Upload images used across the homepage blocks.')
                    ->schema([
                        MediaUploads::image('hero_image', 'Hero image', 'uploads/site'),
                        MediaUploads::image('about_image', 'About / band image', 'uploads/site'),
                        MediaUploads::image('live_video_image', 'Live experience poster', 'uploads/site'),
                        MediaUploads::image('cta_background_image', 'Booking CTA background', 'uploads/site'),
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

        foreach (['hero_image', 'about_image', 'live_video_image', 'cta_background_image'] as $key) {
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
