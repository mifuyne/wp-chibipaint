<?php

/*
  Plugin Name: Chibipaint for Wordpress
  Plugin URI: http://mifuyne.com/works/projects/wp-chibipaint/
  Description: Integrates Chibipaint java applet with Wordpress's backend.
  Author: Mifuyne
  Version: 0.1
  Author URI: http://Mifuyne.org/
 */

// show all chibipaint posts on front page, this one is for the front-end so we're not going to do an admin panel check
add_filter( 'pre_get_posts', 'cbp_get_posts' );
add_action('init', 'cbp_create_pt');

/**
 * Adds the chibipaint applet to the custom post type, "Sketches"
 */
function cbp_editor() {
	// add_meta_box('chibipaint_metabox', 'Chibipaint', 'cbp_display_metabox', 'Sketches', 'normal', 'high');
    if('sketches' == get_post_type()) { // Change Sketches to whatever the user set Custom Post Type slug to
		// The form display has been moved to the cbp_editor class
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


function cbp_menu() {
    if(function_exists('add_menu_page')) {
            add_menu_page('ChibiPaint', 'ChibiPaint', 'publish_posts', 'chibipaint', 'cbp_options');
    }
}

// <editor-fold defaultstate="collapsed" desc="NOTE: cbp_display_metabox() is too far ahead, will activate later ">
function cbp_display_metabox() {
    echo 'Testing';
}
// </editor-fold>

/**
 * Adds a new custom post type for Chibipaint
 * 
 * @param string ptNamePlural   Custom post type name in plural
 * @param string ptNameSingle   Custom post type name in singular
 * @param string ptSlug         Custom post type slug
 * @param string ptDescription  Custom post type description
 * @param int ptMenuPos         Custom post type menu position in the admin menu
 * 
 */
function cbp_create_pt() {
    
    register_post_type( 'sketches',
        array(
            'labels' => array(
                'name' => 'Sketches',
                'singular_name' => 'Sketch',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Sketch',
                'edit' => 'Edit',
                'edit_item' => 'Edit Sketch',
                'new_item' => 'New Sketch',
                'view' => 'View',
                'view_item' => 'View Sketch',
                'search_items' => 'Search Sketches',
                'not_found' => 'No Sketches found',
                'not_found_in_trash' => 'No Sketches found in Trash',
                'parent' => 'Parent Sketch'
            ),
 
            'public' => true,
            'menu_position' => 5,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'taxonomies' => array( 'category', 'post_tag' ),
            'has_archive' => true
        )
    );
}

// Add Sketches to the front page blog, ensure this option can be turned off!
function cbp_get_posts( $query ) {

	if ( is_home() && $query->is_main_query() )
		$query->set( 'post_type', array( 'post', 'sketches' ) );

	return $query;
}

// REQUIRED FILES -- NOT REQUIRED YET!
require_once(dirname(__FILE__).'/inc/cbp_options.php'); // The options/settings screen
require_once(dirname(__FILE__).'/inc/cbp_editor.php'); // The editor class

// Preparatory code if we're opening up the admin page
if (is_admin()) {
	add_action('edit_form_after_title', 'cbp_editor');
	add_action('admin_enqueue_scripts', 'cbp_load_scripts');
	
	// These bits are too far ahead, they'll be reimplemented when we get to that point
	/* add_action('admin_menu', 'cbp_menu');
	add_action('wp_ajax_cbp_save_options', 'cbp_options_ajax_request');
	add_action('wp_ajax_nopriv_cbpAjax', 'cbpAjax'); */
}
?>
