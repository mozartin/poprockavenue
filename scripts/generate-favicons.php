<?php

/**
 * Generate favicon assets from the brand mark.
 * Run: php scripts/generate-favicons.php
 */

$root = dirname(__DIR__);
$public = $root.'/public';
$source = $public.'/images/logo/logo-mark-v1-transparent.png';

if (! is_file($source)) {
    fwrite(STDERR, "Mark not found: {$source}\n");
    exit(1);
}

if (! extension_loaded('gd')) {
    fwrite(STDERR, "GD extension required.\n");
    exit(1);
}

function loadMark(string $path): GdImage
{
    $im = imagecreatefrompng($path);
    imagesavealpha($im, true);

    return $im;
}

function trimMark(GdImage $src): GdImage
{
    $w = imagesx($src);
    $h = imagesy($src);
    $minX = $w;
    $minY = $h;
    $maxX = 0;
    $maxY = 0;

    for ($y = 0; $y < $h; $y++) {
        for ($x = 0; $x < $w; $x++) {
            $rgba = imagecolorat($src, $x, $y);
            $alpha = ($rgba & 0x7F000000) >> 24;
            if ($alpha < 120) {
                $minX = min($minX, $x);
                $minY = min($minY, $y);
                $maxX = max($maxX, $x);
                $maxY = max($maxY, $y);
            }
        }
    }

    if ($maxX < $minX) {
        return $src;
    }

    $tw = $maxX - $minX + 1;
    $th = $maxY - $minY + 1;
    $cropped = imagecreatetruecolor($tw, $th);
    imagealphablending($cropped, false);
    imagesavealpha($cropped, true);
    $transparent = imagecolorallocatealpha($cropped, 0, 0, 0, 127);
    imagefilledrectangle($cropped, 0, 0, $tw, $th, $transparent);
    imagecopy($cropped, $src, 0, 0, $minX, $minY, $tw, $th);

    return $cropped;
}

function fitMark(GdImage $mark, int $size, float $padRatio = 0.04, ?array $bg = null): GdImage
{
    $pad = max(1, (int) round($size * $padRatio));
    $inner = $size - ($pad * 2);
    $mw = imagesx($mark);
    $mh = imagesy($mark);
    $ratio = min($inner / $mw, $inner / $mh);
    $w = max(1, (int) round($mw * $ratio));
    $h = max(1, (int) round($mh * $ratio));

    $canvas = imagecreatetruecolor($size, $size);
    imagealphablending($canvas, false);
    imagesavealpha($canvas, true);

    if ($bg === null) {
        $fill = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
    } else {
        [$r, $g, $b] = $bg;
        $fill = imagecolorallocatealpha($canvas, $r, $g, $b, 0);
    }
    imagefilledrectangle($canvas, 0, 0, $size, $size, $fill);

    imagealphablending($canvas, true);
    imagecopyresampled(
        $canvas,
        $mark,
        (int) (($size - $w) / 2),
        (int) (($size - $h) / 2),
        0,
        0,
        $w,
        $h,
        $mw,
        $mh
    );
    imagealphablending($canvas, false);
    imagesavealpha($canvas, true);

    return $canvas;
}

function writePng(GdImage $im, string $path): void
{
    imagepng($im, $path, 6);
    echo 'Wrote '.$path.' ('.imagesx($im).'x'.imagesy($im).")\n";
}

$mark = trimMark(loadMark($source));

$favicon16 = fitMark($mark, 16, 0.02);
$favicon32 = fitMark($mark, 32, 0.02);
$apple = fitMark($mark, 180, 0.06, [8, 9, 13]);
$icon192 = fitMark($mark, 192, 0.05, [8, 9, 13]);
$icon512 = fitMark($mark, 512, 0.05, [8, 9, 13]);

writePng($favicon16, $public.'/favicon-16x16.png');
writePng($favicon32, $public.'/favicon-32x32.png');
writePng($apple, $public.'/apple-touch-icon.png');
writePng($icon192, $public.'/icon-192.png');
writePng($icon512, $public.'/icon-512.png');

echo "PNG favicons updated. Run Python helper for favicon.ico/svg if needed, or keep existing ICO/SVG from last generation.\n";
