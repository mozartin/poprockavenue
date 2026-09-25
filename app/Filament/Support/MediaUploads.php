<?php

namespace App\Filament\Support;

use App\Support\MediaPath;
use App\Support\OptimizeUploadedImage;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Throwable;

class MediaUploads
{
    public static function image(string $name, string $label, string $directory): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            // Never use ->image(): FilePond's image size plugin hangs when it
            // cannot XHR-fetch the file (www vs apex APP_URL, bad MIME, etc.).
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->fetchFileInformation(false)
            ->previewable(false)
            ->openable(false)
            ->downloadable(false)
            ->maxSize(10240)
            // Do not feed stored paths into FilePond — show them as helper HTML
            // instead. Empty FilePond + dehydrated(false) keeps the DB value.
            ->afterStateHydrated(function (FileUpload $component, mixed $state): void {
                $component->state(self::temporaryOnly($state));
            })
            ->dehydrated(fn (mixed $state): bool => self::hasTemporaryUpload($state))
            ->helperText(function (FileUpload $component): HtmlString|string {
                $record = $component->getRecord();
                $path = null;

                if (is_object($record)) {
                    $path = data_get($record, $component->getName());
                }

                if (! is_string($path) || $path === '') {
                    return 'JPEG / WebP, ideally 200–400KB. Large files are auto-compressed on save.';
                }

                $path = ltrim($path, '/');
                $url = MediaPath::url($path, $path);

                return new HtmlString(
                    '<span class="block text-sm text-gray-500 dark:text-gray-400">Current file — upload a new one to replace.</span>'.
                    '<a href="'.e($url).'" target="_blank" rel="noopener" class="mt-2 inline-block">'.
                    '<img src="'.e($url).'" alt="" class="h-24 max-w-full rounded-lg object-cover" />'.
                    '</a>'
                );
            })
            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) use ($directory): string {
                $stored = $file->store($directory, 'public');

                try {
                    return OptimizeUploadedImage::optimize($stored);
                } catch (Throwable $e) {
                    Log::warning('Image optimize failed: '.$e->getMessage(), ['path' => $stored]);

                    return $stored;
                }
            })
            ->deleteUploadedFileUsing(function (?string $file): void {
                if (filled($file) && Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            });
    }

    public static function video(string $name, string $label, string $directory): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->fetchFileInformation(false)
            ->previewable(false)
            ->afterStateHydrated(function (FileUpload $component, mixed $state): void {
                $component->state(self::temporaryOnly($state));
            })
            ->dehydrated(fn (mixed $state): bool => self::hasTemporaryUpload($state))
            ->maxSize(1048576)
            ->rules(['file', 'max:1048576'])
            ->helperText('Vertical MP4 / MOV / WebM up to 1GB (~10 min). Large files can take a few minutes.');
    }

    protected static function temporaryOnly(mixed $state): mixed
    {
        if ($state instanceof TemporaryUploadedFile) {
            return $state;
        }

        if (! is_array($state)) {
            return null;
        }

        $temps = array_values(array_filter(
            $state,
            fn ($file) => $file instanceof TemporaryUploadedFile
        ));

        return $temps === [] ? null : $temps;
    }

    protected static function hasTemporaryUpload(mixed $state): bool
    {
        if ($state instanceof TemporaryUploadedFile) {
            return true;
        }

        if (! is_array($state)) {
            return false;
        }

        foreach ($state as $file) {
            if ($file instanceof TemporaryUploadedFile) {
                return true;
            }
        }

        return false;
    }
}
