<?php
/*
Plugin Name: MM-email2image
Plugin URI: http://www.mmilan.com/mm-email2image
Description: Mask an e-mail address (or any part of a text) and convert it to image
Author: Milan Milosevic
Author URI: http://www.mmilan.com/
Version: 0.2.2
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


function mm_email2image($attr, $content) {

	$type = $attr['type'];
	$color = $attr['color'];
	$size = $attr['size'];
	$bgcolor = $attr['bgcolor'];
	$trans = $attr['trans'];
	
	$pluginURL = get_bloginfo('url')."/wp-content/plugins/mm-email2image/";	
	global $current_user;
	
	$key = mm_random_e2i_key();
	$enc_content = mm_encrypt($content, $key);

	if (!empty($type)) $mm_e2i_type = $type;
		else $mm_e2i_type = get_option('mm_e2i_type');
	if (!empty($color)) $mm_e2i_textcolor = $color;
		else $mm_e2i_textcolor = substr(get_option('mm_e2i_textcolor'), 1);
	if (!empty($size)) $mm_e2i_textsize = $size;
		else $mm_e2i_textsize = get_option('mm_e2i_textsize');
	if (!empty($bgcolor)) $mm_e2i_bgcolor = $bgcolor;
		else $mm_e2i_bgcolor = substr(get_option('mm_e2i_bgcolor'), 1);
	if (!empty($trans)) $mm_e2i_bgtrans = $trans;
		else $mm_e2i_bgtrans = get_option('mm_e2i_bgtrans');

	get_currentuserinfo();
	$image = '<img style="vertical-align: bottom" src="'.$pluginURL.'e2i.php?string='.$enc_content.'&text='.$key;
	$image .= '&color='.$mm_e2i_textcolor.'&background='.$mm_e2i_bgcolor.'&trans='.$mm_e2i_bgtrans.'&size='.$mm_e2i_textsize.'" />';

	switch ($mm_e2i_type) {
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

	$resultURL = base64_encode($result);
	return strtr($resultURL, '+/=', '-_,');
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

// Admin menu

add_action('admin_head', 'mm_e2i_css');

function mm_e2i_css() {

	$pluginURL = get_bloginfo('url')."/wp-content/plugins/mm-email2image/";
	echo '<script src="'.$pluginURL.'301a.js" type="text/javascript"></script>';
}

add_action('admin_menu', 'mm_e2i_menu');

function mm_e2i_menu() {
	add_options_page('MM email2image', 'MM email2image', 10, __FILE__, 'mm_e2i_opt');
}

function mm_e2i_opt() {
	
	$pluginURL = get_bloginfo('url')."/wp-content/plugins/mm-email2image/";
	$hidden_field_name = 'mm_e2i_submit';

	$mm_e2i_type = get_option('mm_e2i_type');
	$mm_e2i_textcolor = get_option('mm_e2i_textcolor');
	$mm_e2i_textsize = get_option('mm_e2i_textsize');
	$mm_e2i_bgcolor = get_option('mm_e2i_bgcolor');
	$mm_e2i_bgtrans = get_option('mm_e2i_bgtrans');

	if (!is_numeric($mm_e2i_textsize)) $mm_e2i_textsize = 4;

// See if the user has posted us some information
// If they did, this hidden field will be set to 'Y'
	if(isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {

		$mm_e2i_type = $_POST['mm_e2i_type'];
		$mm_e2i_textcolor = $_POST['mm_e2i_textcolor'];
		$mm_e2i_textsize = $_POST['mm_e2i_textsize'];
		$mm_e2i_bgcolor = $_POST['mm_e2i_bgcolor'];
		$mm_e2i_bgtrans = $_POST['mm_e2i_bgtrans'];
		$mm_e2i_textsize = preg_replace("/[^1-5]/", "", $mm_e2i_textsize);
		$mm_e2i_textsize = $mm_e2i_textsize[0];
		if (!is_numeric($mm_e2i_textsize)) $mm_e2i_textsize = 4;

		update_option('mm_e2i_type', $mm_e2i_type);
		update_option('mm_e2i_textcolor', $mm_e2i_textcolor);
		update_option('mm_e2i_textsize', $mm_e2i_textsize);
		update_option('mm_e2i_bgcolor', $mm_e2i_bgcolor);
		update_option('mm_e2i_bgtrans', $mm_e2i_bgtrans);
?>

	<div id="message" class="updated fade">
  		<p><strong>Options saved.</strong></p>
	</div>
<?php	} ?>

	<div style="margin-left: 500px; margin-top: 100px" id="colorpicker301" class="colorpicker301"></div>
	<div class="wrap">
	<h2>MM email2image</h2>
	<p>Default values:</p>
	<form name="mm_e2i_form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
  		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

		<table class="form-table" style="border-bottom: 1px solid #aaa">

			<tr valign="top">
				<th scope="row">Show normal text to:</th>
				<td><SELECT name="mm_e2i_type" />
					<?php if ($mm_e2i_type == "admin") $opt_select = 'selected="selected"'; else $opt_select =""; ?>
					<option value="admin" <?php echo $opt_select ?> >Administrators</option>
					<?php if ($mm_e2i_type == "login") $opt_select = 'selected="selected"'; else $opt_select =""; ?>
					<option value="members" <?php echo $opt_select ?> >Logged in Visitors</option>
					<?php if (($mm_e2i_type != "admin") and ($mm_e2i_type != "login")) $opt_select = 'selected="selected"'; else $opt_select =""; ?>
					<option value="none" <?php echo $opt_select ?> >Show to Everyone</option>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Text color:</th>
				<td>
					<input id="mm_textcolor" type="text" name="mm_e2i_textcolor" value="<?php echo $mm_e2i_textcolor; ?>" /> 
					<img src="<?php echo $pluginURL; ?>select.jpg" onClick="showColorGrid3('mm_textcolor','none');" title="Select color" style="vertical-align: bottom" />
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Background color:</th>
				<td>
					<input id="mm_bgcolor" type="text" name="mm_e2i_bgcolor" value="<?php echo $mm_e2i_bgcolor; ?>" /> 
					<img src="<?php echo $pluginURL; ?>select.jpg" onClick="showColorGrid3('mm_bgcolor','none');" title="Select color" style="vertical-align: bottom"/>
				</td>

				<th scope="row">Transparent background:</th>
				<td><SELECT name="mm_e2i_bgtrans" />
					<?php if ($mm_e2i_bgtrans != "NO") $opt_select = 'selected="selected"'; else $opt_select =""; ?>
					<option value="YES" <?php echo $opt_select ?> >Yes</option>
					<?php if ($mm_e2i_bgtrans == "NO") $opt_select = 'selected="selected"'; else $opt_select =""; ?>
					<option value="NO" <?php echo $opt_select ?> >No</option>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Text size (1-5):</th>
				<td>
					<input type="text" name="mm_e2i_textsize" value="<?php echo $mm_e2i_textsize; ?>" /> 
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
	<p>To use plugin add <em>[e2i]</em> tag in your text editor. Example: <em>[e2i] text to convert to image [/e2i]</em></p>
	<p>You can override default options by adding parametars to the tag:</p>
	<p style="padding-left: 3em">[e2i type="login" color="FFCC88" size="3" bgcolor="0044AA" trans="NO"] text  [/e2i]</p>
	<p>Possible values:
	<ul style="padding-left: 3em">
		<li><strong>type="..."</strong>: show normal text to <em>admin</em>, <em>login</em> or <em>none</em></li>
		<li><strong>color="..."</strong>: text color, <em>000000 - FFFFFF</em></li>
		<li><strong>size="..."</strong>: text size, <em>1 - 5</em></li>
		<li><strong>bgcolor="..."</strong>: background color, <em>000000 - FFFFFF</em></li>
		<li><strong>trans="..."</strong>: transparent background, <em>YES - NO</em></li>
	</ul></p>

	
	</div>


<?php } // end Function?>
