<?php

namespace App\Filament\Support;

use App\Support\OptimizeUploadedImage;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaUploads
{
    public static function image(string $name, string $label, string $directory): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->image()
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            // FilePond hangs on "Waiting for size" when it cannot decode
            // existing files (e.g. PNG saved as .jpg) for the image editor.
            ->fetchFileInformation(false)
            ->maxSize(10240)
            ->helperText('Upload a JPEG or WebP (max 10MB). Prefer ~200–400KB for hero images.')
            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) use ($directory): string {
                $stored = $file->store($directory, 'public');

                return OptimizeUploadedImage::optimize($stored);
            })
            ->deleteUploadedFileUsing(function (?string $file): void {
                if (filled($file) && Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            });
    }

    public static function video(string $name, string $label, string $directory): FileUpload
    {
        // Do not set acceptedFileTypes — FilePond often rejects valid phone videos
        // (empty/odd MIME like application/octet-stream) before they even upload.
        return FileUpload::make($name)
            ->label($label)
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->maxSize(1048576)
            ->rules(['file', 'max:1048576'])
            ->helperText('Vertical MP4 / MOV / WebM up to 1GB (~10 min). Large files can take a few minutes.');
    }
}
