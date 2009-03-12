<?php

$string = $_GET['string'];
$font_size = 4;

$width  = imagefontwidth($font_size)*strlen($string);
$height = imagefontheight($font_size);
$img = imagecreate($width,$height);

$bg    = imagecolorallocate($img, 255, 0, 0);
$color = imagecolorallocate($img, 0, 0, 0);
imagecolortransparent($img, $bg);

$len = strlen($string);
$ypos = 0;

for($i=0; $i<$len; $i++){
	$xpos = $i * imagefontwidth($font_size);
	imagechar($img, $font_size, $xpos, $ypos, $string, $color);
	$string = substr($string, 1);   
}

header("Content-Type: image/gif");
imagegif($img);

imagedestroy($img);

?>
