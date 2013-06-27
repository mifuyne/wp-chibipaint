<?php
/*
  Plugin Name: Chibipaint for Wordpress
  Plugin URI: http://mifuyne.com/works/projects/wp-chibipaint/
  Description: Integrates Chibipaint java applet with Wordpress's backend.
  Author: Mifuyne
  Version: 0.1
  Author URI: http://Mifuyne.org/
 */

register_activation_hook(__FILE__, 'cbp_activate');
function cbp_activate() {
	global $cbpOptions;
	$cbpOptions = get_option('cbp_options');
	
	if (false === $cbpOptions) {
		$cbpOptions = array (
			'cbp_post_types' => array('post' => 'on', 'page' => 'on', 'attachment' => 'off'),
			'cbp_fh_loc' => 'chibi/'
		);
	}
	update_option('cbp_options', $cbpOptions);
}

/**
 * Adds the chibipaint applet to the custom post type, "Sketches"
 */
function cbp_editor() {
	$cbpOptions = get_option('cbp_options');
	$posttypes = $cbpOptions['cbp_post_types'];
	// add_meta_box('chibipaint_metabox', 'Chibipaint', 'cbp_display_metabox', 'Sketches', 'normal', 'high');
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

/**
 * Load up all plugin javascripts for the plugin
 */

function cbp_load_scripts() {
	// Custom CSS
	wp_register_style('cbp-editor-style', plugins_url('/inc/css/cbp-editor.css', __FILE__));
	wp_enqueue_style('cbp-editor-style');
	
	// Handling the applet integration with the Add/Edit post pages
	wp_enqueue_script('cbp-editor', plugin_dir_url(__FILE__) . '/inc/js/cbp-editor.js', array('jquery'));
	
	// <editor-fold defaultstate="collapsed" desc="NOTE: Too far ahead, will activate later ">
	// AJAX -- not yet!
//	wp_enqueue_script( 'cbp-ajax-calls', plugin_dir_url( __FILE__ ) . '/inc/cbp-ajax-calls.js', array( 'jquery' ) );
 	// make the ajaxurl var available to the above script
//	wp_localize_script( 'cbp-ajax-calls', 'cbpAjaxScript', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	
	// wp_enqueue_script("jquery-form");
	// </editor-fold>
}

// REQUIRED FILES -- NOT REQUIRED YET!
require_once(dirname(__FILE__).'/inc/cbp_options.php'); // The options/settings screen
require_once(dirname(__FILE__).'/inc/cbp_editor.php'); // The editor class

// Preparatory code if we're opening up the admin page
if (is_admin()) {
	add_action('edit_form_after_title', 'cbp_editor');
	add_action('admin_enqueue_scripts', 'cbp_load_scripts');
	
	// These bits are too far ahead, they'll be reimplemented when we get to that point
	add_action('admin_menu', 'cbp_add_options_page');
	add_action('admin_init', 'cbp_options_init');
	// add_action('wp_ajax_cbp_save_options', 'cbp_options_ajax_request');
	// add_action('wp_ajax_nopriv_cbpAjax', 'cbpAjax');
}
?>
