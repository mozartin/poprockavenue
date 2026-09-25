<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class MediaPath
{
    /** Uploads larger than this are treated as broken/unoptimized and ignored. */
    public const MAX_UPLOAD_BYTES = 512_000;

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

            if (! self::isUsablePublicFile($withoutPrefix) && filled($default)) {
                return self::url($default);
            }

            return asset($image);
        }

        if (
            str_starts_with($image, 'uploads/')
            || str_starts_with($image, 'media/')
        ) {
            if (! self::isUsablePublicFile($image) && filled($default)) {
                return self::url($default);
            }

            return Storage::disk('public')->url($image);
        }

        if (Storage::disk('public')->exists($image) && self::isUsablePublicFile($image)) {
            return Storage::disk('public')->url($image);
        }

        return asset($image);
    }

    /**
     * Only hydrate Filament FileUpload with small, existing upload paths.
     * Oversized / missing files cause FilePond to hang on "Waiting for size".
     */
    public static function uploadablePath(mixed $path): ?string
    {
        if (! is_string($path) || $path === '') {
            return null;
        }

        $path = ltrim($path, '/');

        if (! str_starts_with($path, 'uploads/') && ! str_starts_with($path, 'media/')) {
            return null;
        }

        return self::isUsablePublicFile($path) ? $path : null;
    }

    public static function isUsablePublicFile(string $relativePath): bool
    {
        $relativePath = ltrim($relativePath, '/');

        if (! Storage::disk('public')->exists($relativePath)) {
            return false;
        }

        $absolute = Storage::disk('public')->path($relativePath);
        $size = @filesize($absolute);

        if ($size === false || $size <= 0) {
            return false;
        }

        return $size <= self::MAX_UPLOAD_BYTES;
    }
}
