=== Plugin Name ===
Contributors: mmilan81
Donate link: http://www.mmilan.com/
Tags: email, spam, protect, image, link, screen, scrape
Requires at least: 2.6
Tested up to: 2.9
Stable tag: 0.2.1

Add new shortcode which can be used to convert email or any part of text to image

== Description ==

This plugin allows you to add a shortcode and convert any part of text to image. Pluging is usefull if you want to protect e-mail addresses or links in your post.

To use plugin just install it and activate. When the plugin is activated you can use [e2i] text you wish to convert to image [/e2i]. If you use this tag you will create image with default optios (you can set them in Option page). Also, you can override default options by adding parametars to the tag:

[e2i type="login" color="FFCC88" size="3" bgcolor="0044AA" trans="NO"] text [/e2i]

Possible values:

    * type="...": show normal text to admin, login or none
    * color="...": text color, 000000 - FFFFFF
    * size="...": text size, 1 - 5
    * bgcolor="...": background color, 000000 - FFFFFF
    * trans="...": transparent background, YES - NO

Changelog:

	2009-12-25, version 0.2.1
		Bugfix: Wordpress 2.9

	2009-03-20, version 0.2
		Add: option page
		Add: choose font size and color, background color and transparency

	2009-03-17, version 0.1.2
		Bugfix: problems in encryption solved

	2009-03-17, version 0.1.1
		Bugfix: added encryption of text tagged for conversion 

	2009-03-12, version 0.1
		First realase!


== Installation ==

1. Upload plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In post/page editor add [e2i]...[/e2i] around text you want to convert to image. See Options page to set default values and for more details about usage.

== Frequently Asked Questions ==

= Are there any plans for new version? =

Yes. Some new features I plan to add:
	* text in multiple lines

== Screenshots ==

1. Options page
