<?php

namespace Imagetrix;



class Imagetrix
{
    public function __construct()
    {        
    }

    public static function resize($image, $width, $height)
    {
        $img    = self::createImage($image);
        $img    = imagescale($img, $width, $height);
        
        return $img;
    }

    private static function createImage($file)
    {
        $info = getimagesize($file);
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image_create = 'imagecreatefromjpeg';
                break;

            case 'image/png':
                $image_create = 'imagecreatefrompng';
                break;

            case 'image/gif':
                $image_create = 'imagecreatefromgif';
                break;

            default: 
                throw new \InvalidArgumentException('Unsupported image type: '.$mime);
        }
        

        return $image_create($file);
    }

    public static function toBinaryMatrix($file, $fudge = 0)
    {
        if (is_string($file)) {
            $img = self::createImage($file);
        } else {
            $img = $file;
        }

        $width  = imagesx($img);
        $height = imagesy($img);
        $matrix = [];
        
        for ($y=0; $y < $height; $y++) {            
            for ($x=0; $x < $width; $x++) {
                
                $rgb   = self::pixelAt($img, $x, $y);
                $pixel = ($rgb['R'] + $rgb['G'] + $rgb['B'])/3;
                
                // value above 0(white) is 1 
                if ($pixel > $fudge) {                    
                    $matrix[$y][$x] = "1";
                } else {
                    $matrix[$y][$x] = "0";                    
                }
            }            
        }
        
        return $matrix;
    }

    public static function toMatrix($file)
    {        
        if (is_string($file)) {
            $img = self::createImage($file);
        } else {
            $img = $file;
        }
            
        $width  = imagesx($img);
        $height = imagesy($img);
        $matrix = [];
        
        for ($y=0; $y < $height; $y++) {            
            for ($x=0; $x < $width; $x++) {
                
                $pixel   = self::pixelAt($img, $x, $y);                
                                
                $matrix[$y][$x] = $pixel;                
            }            
        }
        
        return $matrix;
    }
    
    public static function toMatrixRGB($file)
    {        
        
        $img    = self::createImage($file);
        $width  = imagesx($img);
        $height = imagesy($img);
        $matrix = [];
        
        for ($y=0; $y < $height; $y++) {            
            for ($x=0; $x < $width; $x++) {
                
                $pixel               = self::pixelAt($img, $x, $y);
                $matrix['R'][$y][$x] = $pixel['R'];
                $matrix['G'][$y][$x] = $pixel['G'];
                $matrix['B'][$y][$x] = $pixel['B'];
            }            
        }
        
        return $matrix;
    }

    public static function pixelAt($img, $x, $y)
    {
        if (is_string($img)) {
            $img = self::createImage($img);
        }

        $rgb = imagecolorat($img, $x, $y);                
        $r   = ($rgb >> 16) & 0xFF;
        $g   = ($rgb >> 8 ) & 0xFF;
        $b   = $rgb & 0xFF;

        return ["R" => $r, "G" => $g, "B" => $b];
    }
    
    public static function draw($img, $width, $height)
    {
        $img    = self::resize($img, $width, $height);
        $matrix = self::toBinaryMatrix($img);

        $image = "";
        foreach ($matrix as $line) {
            $image .= implode('', $line).PHP_EOL;                        
        }

        return $image;
    }

    public static function toBinaryVector($file, $fudge = 0)
    {
        $vector = [];
        $matrix = self::toBinaryMatrix($file, $fudge);

        foreach ($matrix as $line) {            
            $vector = array_merge($vector, $line);            
        }
        
        return $vector;
    }
}