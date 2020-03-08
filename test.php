<?php


require __DIR__."/vendor/autoload.php";

use Imagetrix\Imagetrix;
use NumPHP\Core\NumArray;


//$matrix  = (new NumArray(Imagetrix::toMatrixBinary("./image2.png")));
//$matrixA = (Imagetrix::toMatrixRGB("./image2.png"));

//var_dump($matrixA);

print_r(Imagetrix::toBinaryVector("./image2.png", 128));

//echo Imagetrix::draw($matrixA);

//print_r(Imagetrix::pixelAt("./image.png", 100, 100));