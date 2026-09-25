<?php

namespace App\Filament\Support;

use App\Support\OptimizeUploadedImage;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Throwable;

class MediaUploads
{
    public static function image(string $name, string $label, string $directory): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            // Do NOT use ->image(): FilePond's image plugin hangs forever on
            // "Waiting for size" for many production uploads (PNG-as-JPG, large files).
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->fetchFileInformation(false)
            ->previewable(false)
            ->openable(false)
            ->downloadable(false)
            ->maxSize(10240)
            ->helperText('JPEG / WebP, ideally 200–400KB. Large files are auto-compressed on save.')
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
            ->maxSize(1048576)
            ->rules(['file', 'max:1048576'])
            ->helperText('Vertical MP4 / MOV / WebM up to 1GB (~10 min). Large files can take a few minutes.');
    }
}
