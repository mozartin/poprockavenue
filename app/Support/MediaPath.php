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
            $withoutPrefix = substr($image, strlen('storage/'));

            if ($withoutPrefix !== '' && ! Storage::disk('public')->exists($withoutPrefix) && filled($default)) {
                return self::url($default);
            }

            return asset($image);
        }

        if (
            str_starts_with($image, 'uploads/')
            || str_starts_with($image, 'media/')
        ) {
            // Missing upload on this machine (common after DB sync without storage) → default.
            if (! Storage::disk('public')->exists($image) && filled($default)) {
                return self::url($default);
            }

            return Storage::disk('public')->url($image);
        }

        if (Storage::disk('public')->exists($image)) {
            return Storage::disk('public')->url($image);
        }

        return asset($image);
    }
}
