<?php

/*
  Plugin Name: Chibipaint for Wordpress
  Plugin URI: http://mifuyne.com/works/projects/wp-chibipaint/
  Description: Integrates Chibipaint java applet with Wordpress's backend.
  Author: Mifuyne
  Version: 0.1
  Author URI: http://Mifuyne.org/
 */

/* Creating action to implement the java applet metabox
 * We'll be using AJAX to pass parameters and initialize
 * the java applet
 */

add_action('init', 'cbp_create_pt');
/* do_action('admin_init', 'Sketches', 'Sketch', 'sketches', 'Sketches made in Chibipaint', 0); */
add_action('edit_form_after_title', 'cbp_admin');
add_action('admin_menu', 'cbp_menu');

add_filter( 'pre_get_posts', 'cbp_get_posts' );  // show all chibipaint posts on front page

/**
 * Adds all of the necessary components for the plugin, which is injected into `admin_init`
 */
function cbp_admin() {
	// add_meta_box('chibipaint_metabox', 'Chibipaint', 'cbp_display_metabox', 'sketches', 'normal', 'high');
    if('sketches' == get_post_type()) { // Change Sketches to whatever the user set Custom Post Type slug to
        ?>
    <div>
        Chibipaint applet goes here!
    </div>
    <?php
    }
}

function cbp_menu() {
    if(function_exists('add_menu_page')) {
            add_menu_page('ChibiPaint', 'ChibiPaint', 'publish_posts', 'chibipaint', 'cbp_options');
    }
}

function cbp_display_metabox() {
    echo "Testing";
}

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
            'supports' => array( 'title', 'comments', 'thumbnail', 'custom-fields' ),
            'taxonomies' => array( 'category', 'post_tag' ),
            'has_archive' => true
        )
    );
}

// Add Sketches to the front page blog, ensure this option can be turned off!
function cbp_get_posts( $query ) {

	if ( is_home() && $query->is_main_query() )
		$query->set( 'post_type', array( 'post', 'page', 'sketches' ) );

	return $query;
}

// REQUIRED FILES
require_once(dirname(__FILE__).'/inc/cbp_options.php'); // The options/settings screen
?>
