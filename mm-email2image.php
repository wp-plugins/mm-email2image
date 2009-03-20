<?php
/*
Plugin Name: MM-email2image
Plugin URI: http://www.mmilan.com/mm-email2image
Description: Mask an e-mail address (or any part of a text) and convert it to image
Author: Milan Milosevic
Version: 0.1.1
License: GPL v3 - http://www.gnu.org/licenses/

*/


function mm_email2image($attr, $content) {

	$type = $attr['type'];
	$pluginURL = get_bloginfo('url')."/wp-content/plugins/mm-email2image/";	
	global $current_user;
	
	$key = mm_random_e2i_key();
	$enc_content = mm_encrypt($content, $key);

	get_currentuserinfo();
	$image = '<img style="vertical-align: bottom" src="'.$pluginURL.'e2i.php?string='.$enc_content.'&text='.base64_encode($key).'" />';

	switch ($type) {
		case 'admin':
			if ($current_user->user_level == 10) return $content; else return $image;
			break;
		case 'login':
			if ($current_user->id != '') return $content; else return $image;
			break;
		default:
			return $image;
	}
}

add_shortcode('e2i', 'mm_email2image');

function mm_encrypt($string, $key) {
	$result = '';
	
	for($i=0; $i<strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char) + ord($keychar));
		$result .= $char;
	}

	return base64_encode($result);
}

function mm_random_e2i_key() {

	$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
	srand((double)microtime()*1000000);
	$i = 0; $pass = '' ;

	while ($i <= 9) {
		$num = rand() % 35;
		$tmp = substr($chars, $num, 1);
		$pass = $pass . $tmp;
		$i++;
	}

	return $pass;
}

?>