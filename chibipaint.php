<?php
/*
  Plugin Name: Chibipaint for Wordpress
  Plugin URI: http://mifuyne.com/works/projects/wp-chibipaint/
  Description: Integrates Chibipaint java applet with Wordpress"s backend.
  Author: Mifuyne
  Version: 2.0.1
  Author URI: http://Mifuyne.org/
 */
// ---- Upon activation ---- 
register_activation_hook(__FILE__, "cbp_activate");
function cbp_activate() {
	
	global $cbpOptions;
	$cbpOptions = get_option("cbp_options");
	
	if (false === $cbpOptions) {
		$cbpOptions = array (
			"cbp_post_types" => array("post" => "on", "page" => "on", "attachment" => "off"),
			"cbp_fh_loc" => "chibi/",
			"cbp_chiattach" => "on",
			"cbp_def_width" => 250,
			"cbp_def_height" => 250,
		);
	}
	update_option("cbp_options", $cbpOptions);
}

// ---- Adds the chibipaint applet to any post types it"s enabled for
function cbp_editor() {
	$cbpOptions = get_option("cbp_options");
	$posttypes = $cbpOptions["cbp_post_types"];
	// add_meta_box("chibipaint_metabox", "Chibipaint", "cbp_display_metabox", "Sketches", "normal", "high");
	$activePT = array();
	foreach ($posttypes as $key => $enabled) {
		if ($enabled == "on") $activePT[] = $key;
	}
	
	$currType = get_post_type();
    if(in_array($currType, $activePT)) { // Change Sketches to whatever the user set Custom Post Type slug to
		// The form display has been moved to the cbp_canvas function
		cbp_canvas();
    }
}

// ---- Load up all plugin javascripts for the plugin ----
function cbp_load_scripts() {
	// Custom CSS
	wp_register_style("cbp-editor-style", plugins_url("/inc/css/cbp-editor.css", __FILE__));
	wp_enqueue_style("cbp-editor-style");
	
	// Handling the applet integration with the Add/Edit post pages
	wp_enqueue_script("cbp-editor", plugin_dir_url(__FILE__) . "/inc/js/cbp-editor.js", array("jquery"));
}

// REQUIRED FILES
require_once(dirname(__FILE__)."/inc/cbp_options.php"); // The options/settings screen
require_once(dirname(__FILE__)."/inc/cbp_editor.php"); // The editor class

// Preparatory code if we"re opening up the admin page
if (is_admin()) {
	add_action("edit_form_after_title", "cbp_editor");
	add_action("admin_enqueue_scripts", "cbp_load_scripts");
	
	// ---- Options ---- 
	add_action("admin_menu", "cbp_add_pages");
	add_action("admin_init", "cbp_options_init");
}
?>
