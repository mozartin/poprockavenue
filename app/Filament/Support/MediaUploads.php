<?php

namespace App\Filament\Support;

use Filament\Forms\Components\FileUpload;

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
            ->imageEditor()
            ->maxSize(10240)
            ->helperText('Upload an image (max 10MB).');
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
