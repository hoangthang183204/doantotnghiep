<?php
$dir = __DIR__ . '/public/icons';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}
if (!function_exists('imagecreatetruecolor')) {
    fwrite(STDERR, "no-gd\n");
    exit(2);
}
foreach ([192, 512] as $size) {
    $im = imagecreatetruecolor($size, $size);
    $bg = imagecolorallocate($im, 29, 78, 216);
    imagefilledrectangle($im, 0, 0, $size, $size, $bg);
    $white = imagecolorallocate($im, 255, 255, 255);
    imagefilledellipse($im, (int) ($size / 2), (int) ($size * 0.42), (int) ($size * 0.34), (int) ($size * 0.4), $white);
    imagefilledrectangle($im, (int) ($size * 0.38), (int) ($size * 0.58), (int) ($size * 0.62), (int) ($size * 0.82), $white);
    imagepng($im, $dir . '/icon-' . $size . '.png');
}
echo "icons-ok\n";
