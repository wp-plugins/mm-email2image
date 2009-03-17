<?php

$key = base64_decode($_GET['text']);
$enc_string = $_GET['string'];
$string = mm_decrypt($enc_string, $key);

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


function mm_decrypt($string, $key) {
	$result = '';
	$string = base64_decode($string);

	for($i=0; $i<strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)-ord($keychar));
		$result.=$char;
	}

	return $result;
}

?>
