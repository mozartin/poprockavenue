<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class OptimizeUploadedImage
{
    /**
     * Re-encode an image on the public disk as a progressive JPEG.
     * Returns the (possibly renamed) relative path.
     */
    public static function optimize(string $relativePath, int $maxEdge = 1920, int $quality = 78): string
    {
        $relativePath = ltrim($relativePath, '/');
        $absolute = Storage::disk('public')->path($relativePath);

        if (! is_file($absolute)) {
            return $relativePath;
        }

        $image = self::load($absolute);

        if ($image === false) {
            return $relativePath;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        if (max($width, $height) > $maxEdge) {
            $scale = $maxEdge / max($width, $height);
            $newWidth = (int) round($width * $scale);
            $newHeight = (int) round($height * $scale);
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        imageinterlace($image, true);

        $directory = trim(dirname($relativePath), '.');
        $basename = pathinfo($relativePath, PATHINFO_FILENAME);
        $targetRelative = ($directory !== '' ? $directory.'/' : '').$basename.'.jpg';
        $targetAbsolute = Storage::disk('public')->path($targetRelative);

        if (! is_dir(dirname($targetAbsolute))) {
            mkdir(dirname($targetAbsolute), 0755, true);
        }

        imagejpeg($image, $targetAbsolute, $quality);
        imagedestroy($image);

        if ($targetRelative !== $relativePath && is_file($absolute)) {
            @unlink($absolute);
        }

        return $targetRelative;
    }

    /**
     * @return \GdImage|false
     */
    protected static function load(string $absolute): mixed
    {
        $bytes = @file_get_contents($absolute, false, null, 0, 16);

        if ($bytes === false) {
            return false;
        }

        // Detect by magic bytes — Filament uploads are often PNG with a .jpg name.
        if (str_starts_with($bytes, "\x89PNG")) {
            return @imagecreatefrompng($absolute);
        }

        if (str_starts_with($bytes, "\xFF\xD8\xFF")) {
            return @imagecreatefromjpeg($absolute);
        }

        if (str_starts_with($bytes, 'RIFF') && str_contains($bytes, 'WEBP')) {
            return function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($absolute) : false;
        }

        if (str_starts_with($bytes, 'GIF8')) {
            return @imagecreatefromgif($absolute);
        }

        $mime = @mime_content_type($absolute) ?: '';

        return match (true) {
            str_contains($mime, 'png') => @imagecreatefrompng($absolute),
            str_contains($mime, 'jpeg'), str_contains($mime, 'jpg') => @imagecreatefromjpeg($absolute),
            str_contains($mime, 'webp') && function_exists('imagecreatefromwebp') => @imagecreatefromwebp($absolute),
            str_contains($mime, 'gif') => @imagecreatefromgif($absolute),
            default => @imagecreatefromstring((string) file_get_contents($absolute)),
        };
    }

    public static function optimizePublicFile(string $absolutePath, int $maxEdge = 1920, int $quality = 78): bool
    {
        if (! is_file($absolutePath)) {
            return false;
        }

        $image = self::load($absolutePath);

        if ($image === false) {
            return false;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        if (max($width, $height) > $maxEdge) {
            $scale = $maxEdge / max($width, $height);
            $newWidth = (int) round($width * $scale);
            $newHeight = (int) round($height * $scale);
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        imageinterlace($image, true);
        $ok = imagejpeg($image, $absolutePath, $quality);
        imagedestroy($image);

        return $ok;
    }
}
