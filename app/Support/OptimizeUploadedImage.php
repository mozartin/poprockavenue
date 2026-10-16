<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use RuntimeException;

class OptimizeUploadedImage
{
    /**
     * Re-encode an image on the public disk as a progressive JPEG.
     * Returns the (possibly renamed) relative path.
     *
     * @throws RuntimeException when a HEIC/HEIF file cannot be decoded
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
            if (self::isHeic($absolute)) {
                throw new RuntimeException(
                    'Could not convert this HEIC/HEIF photo. Upload a JPEG or WebP instead.'
                );
            }

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

        if (self::isHeic($absolute, $bytes)) {
            return self::loadViaImagick($absolute) ?: self::loadViaHeifConvert($absolute);
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

    protected static function isHeic(string $absolute, ?string $bytes = null): bool
    {
        $bytes ??= @file_get_contents($absolute, false, null, 0, 16) ?: '';
        $mime = @mime_content_type($absolute) ?: '';
        $extension = strtolower(pathinfo($absolute, PATHINFO_EXTENSION));

        return str_contains($mime, 'heic')
            || str_contains($mime, 'heif')
            || in_array($extension, ['heic', 'heif', 'hec'], true)
            || self::looksLikeHeif($bytes);
    }

    /**
     * HEIF containers start with an ISO BMFF "ftyp" box (same family as MP4).
     */
    protected static function looksLikeHeif(string $bytes): bool
    {
        if (strlen($bytes) < 12 || substr($bytes, 4, 4) !== 'ftyp') {
            return false;
        }

        $brand = substr($bytes, 8, 4);

        return in_array($brand, ['heic', 'heif', 'mif1', 'msf1', 'hevx', 'hevc'], true);
    }

    /**
     * @return \GdImage|false
     */
    protected static function loadViaImagick(string $absolute): mixed
    {
        if (! extension_loaded('imagick') || ! class_exists(\Imagick::class)) {
            return false;
        }

        try {
            $imagick = new \Imagick($absolute);
            $imagick->setImageFormat('jpeg');
            $blob = $imagick->getImageBlob();
            $imagick->clear();
            $imagick->destroy();

            return @imagecreatefromstring($blob);
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * CLI fallback for production Docker (no Imagick extension).
     *
     * @return \GdImage|false
     */
    protected static function loadViaHeifConvert(string $absolute): mixed
    {
        $binary = self::heifConvertBinary();

        if ($binary === null) {
            return false;
        }

        $tmp = sys_get_temp_dir().'/heic_'.uniqid('', true).'.jpg';
        $command = sprintf(
            '%s %s %s 2>/dev/null',
            escapeshellarg($binary),
            escapeshellarg($absolute),
            escapeshellarg($tmp),
        );

        exec($command, $output, $exitCode);

        if ($exitCode !== 0 || ! is_file($tmp) || filesize($tmp) === 0) {
            @unlink($tmp);

            return false;
        }

        $image = @imagecreatefromjpeg($tmp);
        @unlink($tmp);

        return $image;
    }

    protected static function heifConvertBinary(): ?string
    {
        foreach (['heif-convert', '/usr/bin/heif-convert'] as $candidate) {
            if ($candidate === 'heif-convert') {
                $which = trim((string) shell_exec('command -v heif-convert 2>/dev/null'));

                if ($which !== '' && is_executable($which)) {
                    return $which;
                }

                continue;
            }

            if (is_executable($candidate)) {
                return $candidate;
            }
        }

        return null;
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
