<?php

namespace coder;

class PngCoder
{
    private $gd;
    private $x;
    private $y;
    private $side;
    private $separator = ['000000', 'ffffff', '000000'];
    private $ending = '';

    public function encode($path, $dest) {
        $this->x = 0;
        $this->y = 0;
        $contents = file_get_contents($path);
        $hex = bin2hex($contents);
        $size = strlen($hex);
        $this->side = ceil(sqrt($size / 6 + count($this->separator) + 2));
        $this->gd = imagecreatetruecolor($this->side, $this->side);
        for ($i = 0; $i <= $size; $i+= 6) {
            $color = substr($hex, $i, 6);
            if (strlen($color) < 6) {
                $this->drawEnding($color);
                break;
            }
            $this->drawPixel($color);
        }
        $this->saveImage($dest);
    }

    public function decode($path, $dest) {
        $this->ending = '';
        $this->side = getimagesize($path)[0];
        $this->gd = imagecreatefrompng($path);
        $result = '';
        for ($y = 0; $y < $this->side; $y++) {
            for ($x = 0; $x < $this->side; $x++) {
                $rgb = imagecolorat($this->gd, $x, $y);
                $hex = $this->rgb2hex($rgb);
                if ($this->isEnding($x, $y)) {
                    $result .= $this->ending;
                    break 2;
                }
                $result .= $hex;
            }
        }
        $bin = hex2bin($result);
        file_put_contents($dest, $bin);
    }

    private function isEnding($x, $y) {
        $this->x = $x;
        $this->y = $y;
        for ($i = 0; $i < count($this->separator); $i++) {
            $hex = $this->rgb2hex(imagecolorat($this->gd, $this->x, $this->y));
            if ($hex !== $this->separator[$i]) {
                return false;
            }
            $this->incrementX();
        }
        $length = (int) $this->rgb2hex(imagecolorat($this->gd, $this->x, $this->y));
        $this->incrementX();
        $hex = $this->rgb2hex(imagecolorat($this->gd, $this->x, $this->y));
        $this->ending = substr($hex, 6 - $length);
        return true;
    }

    private function incrementX() {
        $this->x++;
        if ($this->x >= $this->side) {
            $this->x = 0;
            $this->y++;
        }
    }

    private function rgb2hex($rgb) {
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        $hex = '';
        $hex .= str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
        $hex .= str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
        $hex .= str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
        return $hex;
    }

    private function drawPixel($color) {
        $r = hexdec(substr($color,0,2));
        $g = hexdec(substr($color,2,2));
        $b = hexdec(substr($color,4,2));
        $colorInt = imagecolorallocate($this->gd, $r, $g, $b);
        imagesetpixel($this->gd, $this->x, $this->y, $colorInt);
        $this->incrementX();
    }

    private function drawEnding($colorRaw) {
        foreach ($this->separator as $color) {
            $this->drawPixel($color);
        }
        $color = str_pad(strlen($colorRaw), 6, '0', STR_PAD_LEFT);
        $this->drawPixel($color);
        $color = str_pad($colorRaw, 6, '0', STR_PAD_LEFT);
        $this->drawPixel($color);
    }

    private function saveImage($path) {
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        return imagepng($this->gd, $path);
    }
}