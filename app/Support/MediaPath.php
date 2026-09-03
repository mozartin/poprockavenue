<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class MediaPath
{
    public static function url(?string $path, ?string $default = null): string
    {
        $image = $path ?: $default;

        if (! filled($image)) {
            return '';
        }

        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }

        $image = ltrim($image, '/');

        if (str_starts_with($image, 'storage/')) {
            return asset($image);
        }

        if (
            str_starts_with($image, 'uploads/')
            || str_starts_with($image, 'media/')
            || Storage::disk('public')->exists($image)
        ) {
            return Storage::disk('public')->url($image);
        }

        return asset($image);
    }
}
