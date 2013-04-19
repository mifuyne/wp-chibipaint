Wordpress plugin for Chibipaint integration

Intro
=============

The aim is to allow for users to create drawings and sketches directly on the blog without having to upload anything from their computer. The java applet, Chibipaint was developed by Marc Schefer. The applet is available under the GNU Public licence v. 3 and can be downloaded at http://www.chibipaint.com/

This will be a complete rewrite from the version I've made a few years ago, which is still avaialble on Wordpress plugin directory.


=== Chibipaint for Wordpress (wp-chibipaint) ===
Contributors: mifuyne
Donate link: --
Tags: admin, chibipaint, drawing, media, painter
Requires at least: 3.5.1
Tested up to: 3.5.1
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Integrating Chibipaint drawing applet with Wordpress.

== Description ==

The aim of the plugin is to integrate it with Wordpress's back end. There's no plans for implementing it for comments.

== Installation ==

If you downloaded this manually:

1. Upload the plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

If you're installing this through Wordpress's plugin installer:

1. Click on the Install button.
2. Once the plugin's installed, activate the plugin.

== Changelog ==

= 0.1.2 =
* Created a new custom post type
* Applet integration is limited to the post type created by the plugin
* Applet settings then applet initialization implemented
* TODO
	* Image submission is NOT IMPLEMENTED YET. It will in the next update.
	* Major code clean up.

= 0.1 =
First re-release of the plugin.