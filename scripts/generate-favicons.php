<?php

/**
 * Generate favicon PNG/ICO assets from brand colors.
 * Run: php scripts/generate-favicons.php
 */

$root = dirname(__DIR__);
$public = $root.'/public';
$font = '/System/Library/Fonts/Supplemental/Arial Bold.ttf';

if (! is_file($font)) {
    fwrite(STDERR, "Font not found: {$font}\n");
    exit(1);
}

function hexRgb(string $hex): array
{
    $hex = ltrim($hex, '#');

    return [
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2)),
    ];
}

function lerp(float $a, float $b, float $t): float
{
    return $a + ($b - $a) * $t;
}

function makeIcon(int $size, string $font, bool $rounded = false): Imagick
{
    $stops = [
        [0.0, hexRgb('#7C3AED')],
        [0.5, hexRgb('#4F46E5')],
        [1.0, hexRgb('#22D3EE')],
    ];

    $im = new Imagick;
    $im->newImage($size, $size, new ImagickPixel('transparent'));
    $im->setImageFormat('png');
    $im->setImageColorspace(Imagick::COLORSPACE_SRGB);

    $draw = new ImagickDraw;
    for ($x = 0; $x < $size; $x++) {
        $t = $size === 1 ? 0.0 : $x / ($size - 1);
        $color = $stops[0][1];
        for ($i = 0; $i < count($stops) - 1; $i++) {
            [$t0, $c0] = $stops[$i];
            [$t1, $c1] = $stops[$i + 1];
            if ($t >= $t0 && $t <= $t1) {
                $local = ($t1 - $t0) < 0.0001 ? 0.0 : ($t - $t0) / ($t1 - $t0);
                $color = [
                    (int) round(lerp($c0[0], $c1[0], $local)),
                    (int) round(lerp($c0[1], $c1[1], $local)),
                    (int) round(lerp($c0[2], $c1[2], $local)),
                ];
                break;
            }
        }
        $draw->setFillColor(sprintf('rgb(%d,%d,%d)', $color[0], $color[1], $color[2]));
        $draw->rectangle($x, 0, $x + 1, $size);
    }
    $im->drawImage($draw);

    if ($rounded) {
        $radius = (int) round($size * 0.22);
        $mask = new Imagick;
        $mask->newImage($size, $size, new ImagickPixel('transparent'));
        $mask->setImageFormat('png');
        $maskDraw = new ImagickDraw;
        $maskDraw->setFillColor('white');
        $maskDraw->roundRectangle(0, 0, $size - 1, $size - 1, $radius, $radius);
        $mask->drawImage($maskDraw);
        $im->compositeImage($mask, Imagick::COMPOSITE_DSTIN, 0, 0);
        $mask->clear();
        $mask->destroy();
    }

    // Typography: P / R
    $fontSize = $size <= 16 ? (int) round($size * 0.52) : (int) round($size * 0.48);
    $text = new ImagickDraw;
    $text->setFont($font);
    $text->setFontSize($fontSize);
    $text->setFillColor('white');
    $text->setTextAntialias(true);

    $metricsP = $im->queryFontMetrics($text, 'P');
    $metricsR = $im->queryFontMetrics($text, 'R');
    $baseline = ($size + $metricsP['ascender'] + $metricsP['descender']) / 2;

    $gap = $size * ($size <= 16 ? 0.10 : 0.08);
    $slashW = max(1.5, $size * 0.07);
    $contentW = $metricsP['textWidth'] + $gap + $slashW + $gap + $metricsR['textWidth'];
    $startX = ($size - $contentW) / 2;

    $im->annotateImage($text, $startX, $baseline, 0, 'P');
    $im->annotateImage($text, $startX + $metricsP['textWidth'] + $gap + $slashW + $gap, $baseline, 0, 'R');

    $slash = new ImagickDraw;
    $slash->setFillColor('white');
    $slash->setStrokeColor('white');
    $slash->setStrokeWidth(0);
    $sx = $startX + $metricsP['textWidth'] + $gap + ($slashW / 2);
    $top = $size * 0.28;
    $bottom = $size * 0.72;
    $half = $slashW / 2;
    $slash->polygon([
        ['x' => $sx + $half, 'y' => $top],
        ['x' => $sx + $half + $size * 0.02, 'y' => $top],
        ['x' => $sx - $half + $size * 0.02, 'y' => $bottom],
        ['x' => $sx - $half, 'y' => $bottom],
    ]);
    $im->drawImage($slash);

    return $im;
}

function writePng(Imagick $im, string $path): void
{
    $im->setImageFormat('png');
    $im->stripImage();
    file_put_contents($path, $im->getImageBlob());
    echo "Wrote {$path} ({$im->getImageWidth()}x{$im->getImageHeight()})\n";
}

$favicon16 = makeIcon(16, $font, rounded: false);
$favicon32 = makeIcon(32, $font, rounded: false);
$apple = makeIcon(180, $font, rounded: true);
$icon192 = makeIcon(192, $font, rounded: true);

writePng($favicon16, $public.'/favicon-16x16.png');
writePng($favicon32, $public.'/favicon-32x32.png');
writePng($apple, $public.'/apple-touch-icon.png');
writePng($icon192, $public.'/icon-192.png');

// Build multi-size ICO (16 + 32)
$ico = new Imagick;
$ico->addImage(clone $favicon16);
$ico->addImage(clone $favicon32);
$ico->setFormat('ico');
file_put_contents($public.'/favicon.ico', $ico->getImagesBlob());
echo "Wrote {$public}/favicon.ico\n";

$favicon16->clear();
$favicon32->clear();
$apple->clear();
$icon192->clear();
$ico->clear();
