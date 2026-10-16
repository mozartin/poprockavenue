<?php

namespace App\Filament\Support;

use App\Filament\Forms\Components\ChunkedVideoUpload;
use App\Support\MediaPath;
use App\Support\OptimizeUploadedImage;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;
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
            ->acceptedFileTypes([
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/gif',
                'image/heic',
                'image/heif',
            ])
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->fetchFileInformation(false)
            ->previewable(false)
            ->openable(false)
            ->downloadable(false)
            ->maxSize(10240)
            // Never put DB paths into FilePond (it hangs on size fetch). Show the
            // current file via helperText instead. Empty FilePond + dehydrated(false)
            // keeps the existing DB value on save.
            ->afterStateHydrated(function (FileUpload $component, mixed $state): void {
                $component->state(self::temporaryOnly($state));
            })
            // saveUploadedFiles() runs before dehydrate and replaces TemporaryUploadedFile
            // with a stored path — so we must dehydrate path strings too.
            ->dehydrated(fn (mixed $state): bool => self::shouldPersistUpload($state))
            ->helperText(function (FileUpload $component): HtmlString|string {
                $record = $component->getRecord();
                $path = null;

                if (is_object($record)) {
                    $path = data_get($record, $component->getName());
                }

                if (! is_string($path) || $path === '') {
                    return 'JPEG / WebP / HEIC, ideally 200–400KB. HEIC and large files are auto-converted/compressed on save.';
                }

                $path = ltrim($path, '/');
                $url = MediaPath::url($path, $path);

                return self::currentImageHelper($url, 'Current file — upload a new one to replace.');
            })
            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) use ($name, $directory): string {
                $stored = $file->store($directory, 'public');

                try {
                    return OptimizeUploadedImage::optimize($stored);
                } catch (Throwable $e) {
                    if (Storage::disk('public')->exists($stored)) {
                        Storage::disk('public')->delete($stored);
                    }

                    Log::warning('Image optimize failed: '.$e->getMessage(), ['path' => $stored]);

                    throw ValidationException::withMessages([
                        $name => $e->getMessage() ?: 'Could not process this image. Try JPEG or WebP.',
                    ]);
                }
            })
            ->deleteUploadedFileUsing(function (?string $file): void {
                if (filled($file) && Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            });
    }

    public static function video(string $name, string $label, string $directory): ChunkedVideoUpload
    {
        // Chunked upload bypasses Livewire's single-request temp upload, which
        // Coolify/Traefik kills at ~60s (502) for ~200MB+ files on typical links.
        return ChunkedVideoUpload::make($name)
            ->label($label)
            ->directory($directory)
            ->helperText('MP4 / MOV / WebM up to 1GB (~10 min). Any orientation — preview cards stay vertical, the player opens at the video\'s own aspect ratio. Large files upload in small chunks.');
    }

    /**
     * Small thumbnail under FilePond. Inline size so Filament/prose CSS cannot stretch it full-width.
     */
    public static function currentImageHelper(string $url, string $hint): HtmlString
    {
        return new HtmlString(
            '<span class="block text-sm text-gray-500 dark:text-gray-400">'.e($hint).'</span>'.
            '<a href="'.e($url).'" target="_blank" rel="noopener" class="mt-2 inline-block">'.
            '<img src="'.e($url).'" alt="" loading="lazy" width="96" height="96" '.
            'style="display:block;width:96px;height:96px;max-width:96px;max-height:96px;object-fit:contain;border-radius:0.5rem;background:#111;" />'.
            '</a>'
        );
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

    /**
     * Persist when the user uploaded a new file this request.
     * Empty FilePond (null / []) stays non-dehydrated so the DB path is kept.
     */
    protected static function shouldPersistUpload(mixed $state): bool
    {
        if ($state instanceof TemporaryUploadedFile) {
            return true;
        }

        if (is_string($state)) {
            return $state !== '';
        }

        if (! is_array($state) || $state === []) {
            return false;
        }

        foreach ($state as $file) {
            if ($file instanceof TemporaryUploadedFile) {
                return true;
            }

            if (is_string($file) && $file !== '') {
                return true;
            }
        }

        return false;
    }
}
