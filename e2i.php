<?php

$key = $_GET['text'];
$enc_string = $_GET['string'];
$string = mm_decrypt($enc_string, $key);

$font_size = 4;

$width  = imagefontwidth($font_size)*strlen($string);
$height = imagefontheight($font_size);
$img = imagecreate($width,$height);

$bg    = imagecolorallocate($img, 255, 0, 0);
$color = imagecolorallocate($img, 255, 255, 255);
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
	$string64url = strtr($string, '-_,', '+/=');
	$string = base64_decode($string64url);

    $base64url = strtr($plainText, '-_,', '+/=');
    $base64 = base64_decode($base64url);

	for($i=0; $i<strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)-ord($keychar));
		$result.=$char;
	}

	return $result;
}

?>
