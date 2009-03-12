<?php
/*
Plugin Name: MM-email2image
Plugin URI: http://www.mmilan.com/mm-email2image
Description: Mask an e-mail address (or any part of a text) and convert it to image
Author: Milan Milosevic
Version: 0.1
License: GPL v3 - http://www.gnu.org/licenses/

*/


function mm_email2image($attr, $content) {

	$type = $attr['type'];
	$pluginURL = get_bloginfo('url')."/wp-content/plugins/mm-email2image/";	
	global $current_user;

	get_currentuserinfo();
	$image = '<img style="vertical-align: bottom" src="'.$pluginURL.'e2i.php?string='.$content.'" />';

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

?>