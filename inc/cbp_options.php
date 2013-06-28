<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
// ob_start();

// TODO (06/28/2013): Force users to come here and set up!
function cbp_add_options_page() {
	add_options_page('Chibipaint Integration Settings', 'Chibipaint Integration', 'manage_options', 'options_cbp', 'cbp_display_options_page');
}
function cbp_display_options_page() {
	?>
	<div>
	<h2>Chibipaint Integration Settings</h2>
	All fields are required.
	<form action="options.php" method="post">
	<?php settings_fields('cbp_option_group');
	do_settings_sections('options_cbp');
	submit_button();
	?>
	</form></div>

	<?php
}

function cbp_options_init(){
	/* TODO: refer to http://kovshenin.com/2012/the-wordpress-settings-api/ on
	 * how to pass multiple variables to a function (Reusing Controls with the
	 * Settings API).
	 */
	
	// TODO: (06/16/2013) Dimensions, adding via AJAX
	$cbpOptions = get_option('cbp_options');
	
	register_setting( 'cbp_option_group', 'cbp_options', 'cbp_validate_options' );
	add_settings_section('cbp_gen_options', 'General Options', 'cbp_gen_section_text', 'options_cbp');
	add_settings_field('cbp_post_types', 'Which post types should the editor be available for?', 'cbp_posttype_array', 'options_cbp', 'cbp_gen_options', 
		array('name' => 'cbp_options[cbp_post_types]',
			'value' => $cbpOptions["cbp_post_types"]
		));
	add_settings_field('cbp_fh_loc', 'Save to? (currently disabled)', 'cbp_string_input', 'options_cbp', 'cbp_gen_options', 
			array('name' => 'cbp_options[cbp_fh_loc]',
				'value' => $cbpOptions["cbp_fh_loc"],
				'prefix' => 'wp-content/uploads/',
				'id' => 'cbp_fh_loc'
			));
}

function cbp_gen_section_text() {
	echo '<p>General options for the integration plugin.</p>';
}

function cbp_string_input( $arg ) {
	// $arg = name, value, prefix, id, disable (in no order)
	extract($arg);

	$disabled = "";
	if ($disable) {
		$disabled = "disabled='disabled'";
	}
	
	$inputField = "<input type='text' name='$name' value='$value' id='$id' $disabled/>";
	echo "<p>" . $prefix . $inputField . "</p>";
}

function cbp_posttype_array( $arg ) {
	extract($arg);
	
	$checked = array();
	
	$inputField = "<input type='checkbox' name='$name' id='$id' $disabled $checked/>";
	
	$posttypes = get_post_types(array('public' => true));

	// Start the list
	echo "<fieldset><ul class=\"cbp-pt-list\">";
	foreach ($posttypes as $key => $pt) {
		if ($value[$key] == "on") $checked[$key] = "checked='checked'";
		else $checked[$key] = "";
		
		// name='".$name."[$key]' creates an an associative array inside the options array for which posts the user wants to enable
		echo "	<li class='cbp-pt-entry'>
					<input type='hidden' name='".$name."[$key]' value='off' />
					<label for='cbp-$pt'><input type='checkbox' name='".$name."[$key]' id='cbp-$pt' $disabled $checked[$key] /> " . ucfirst($pt) . "</label>
				</li>";
	}
	echo "</ul></fieldset>";
	
}

function cbp_validate_options($input) {
	// Output buffer for debug, remove in release
	ob_start();
	
	echo "Input:";
	print_r($input);
	
	$cbpOptions = get_option('cbp_options');
	$cbpOptions['cbp_post_types'] = array();
	
	// assign on or off according to user input
	foreach($input['cbp_post_types'] as $key => $value) {
		$cbpOptions['cbp_post_types'][$key] = $input['cbp_post_types'][$key];
	}
	
	$cbpOptions['cbp_fh_loc'] = sanitize_text_field($input['cbp_fh_loc']);
	
	echo "CBP Options:";
	print_r($cbpOptions);
	
	$contents = ob_get_flush();
	file_put_contents(dirname(__FILE__)."/option_log.txt", $contents);
	
	return $cbpOptions;
}
?>