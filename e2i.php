<?php
/*
Description: Create image for input text
Author: Milan Milosevic
License: GPL v3 - http://www.gnu.org/licenses/


    Copyright 2009  Milan Milosevic  (email : mm@mmilan.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$key = $_GET['text'];
$enc_string = $_GET['string'];
$string = mm_decrypt($enc_string, $key);
$textcolor = $_GET['color'];
$bgcolor = $_GET['background'];
$bgtrans = $_GET['trans'];
$font_size = $_GET['size'];

if (!validHexColor($textcolor)) $textcolor = "FFFFFF";
if (!validHexColor($bgcolor)) $bgcolor = "00AAAA";

$t_rgb = sscanf($textcolor, '%2x%2x%2x');
$b_rgb = sscanf($bgcolor, '%2x%2x%2x');

$font_size = preg_replace("/[^1-5]/", "", $font_size);
$font_size = $font_size[0];
if (!is_numeric($font_size)) $font_size = 4;

$width  = imagefontwidth($font_size)*strlen($string);
$height = imagefontheight($font_size);
$img = imagecreate($width,$height);

$bg    = imagecolorallocate($img, $b_rgb[0], $b_rgb[1], $b_rgb[2]);
$color = imagecolorallocate($img, $t_rgb[0], $t_rgb[1], $t_rgb[2]);

if ($bgtrans != 'NO') imagecolortransparent($img, $bg);

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


function validHexColor($color) {
    $color = substr($color, 0, 7);
    return preg_match('/[0-9a-fA-F]{6}/', $color);
}

function mm_decrypt($string, $key) {
	$result = '';
	$string64url = strtr($string, '-_,', '+/=');
	$string = base64_decode($string64url);

	for($i=0; $i<strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)-ord($keychar));
		$result.=$char;
	}

	return $result;
}

?>
