<?php

class Zynas_Imagemagick {

    private static $_pathConvert = '/usr/bin/convert';
    private static $_pathComposite = '/usr/bin/composite';

    public static function watermark($source, $target, $watermark, $gravity = 'southeast') {
        exec(escapeshellcmd(self::$_pathComposite . ' ' . $watermark . ' ' . $source . ' -gravity ' . $gravity . ' ' . $target));
    }

    public static function resize($source, $target, $maxWidth, $maxHeight, $isSizeForce = false) {
        exec(escapeshellcmd(self::$_pathConvert . ' -resize ' . intval($maxWidth) . 'x' . intval($maxHeight) . ($isSizeForce ? '!' : '>') . ' ' . $source . ' ' . $target));
    }

    public static function composite($image1, $image2, $outImage) {
        exec(escapeshellcmd(self::$_pathConvert . ' ' . $image1 . ' ' . $image2 . ' -composite ' . $outImage));
    }

}

?>