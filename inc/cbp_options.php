<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// ---- Puts the option page under Settings ---- 
function cbp_add_pages() {
	add_menu_page("Chibipaint Integration", "Chibipaint", "manage_options", "chibipaint", "cbp_display_front_page");
	add_submenu_page("chibipaint", "Chibipaint Integration Options", "Options", "manage_options", "cbp_options", "cbp_display_options_page");
	add_submenu_page("chibipaint", "Chibipaint Help", "Help", "manage_options", "cbp_help", "cbp_display_help_page");
}

// ---- Option initialization ---- 
function cbp_options_init(){
	$cbpOptions = get_option("cbp_options");
	
	register_setting( "cbp_option_group", "cbp_options", "cbp_validate_options" );
	add_settings_section("cbp_gen_options", "General Options", "cbp_gen_section_text", "options_cbp");
	add_settings_field("cbp_post_types", "Post type integration", "cbp_posttype_array", "options_cbp", "cbp_gen_options", 
		array("name" => "cbp_options[cbp_post_types]",
			"value" => $cbpOptions["cbp_post_types"]
		));
	add_settings_field("cbp_fh_loc", "Save location (blank = default upload directory)", "cbp_string_input", "options_cbp", "cbp_gen_options", 
			array("name" => "cbp_options[cbp_fh_loc]",
				"value" => $cbpOptions["cbp_fh_loc"],
				"prefix" => "wp-content/uploads/",
				"id" => "cbp_fh_loc"
			));
	add_settings_field("cbp_chiattach", "Add source files to Media Library", "cbp_check_input", "options_cbp", "cbp_gen_options", 
			array("name" => "cbp_options[cbp_chiattach]",
				"value" => $cbpOptions["cbp_chiattach"],
				"id" => "cbp_chiattach"
			));
	add_settings_section("cbp_dim_options", "Dimensions", "cbp_dim_section_text", "options_cbp");
	add_settings_field("cbp_def_width", "Default Width (in pixels)", "cbp_string_input", "options_cbp", "cbp_dim_options", 
			array("name" => "cbp_options[cbp_def_width]",
				"value" => $cbpOptions["cbp_def_width"],
				"id" => "cbp_def_width"
			));
	add_settings_field("cbp_def_height", "Default Height (in pixels)", "cbp_string_input", "options_cbp", "cbp_dim_options", 
			array("name" => "cbp_options[cbp_def_height]",
				"value" => $cbpOptions["cbp_def_height"],
				"id" => "cbp_def_height"
			));
}

// ---- General Section text ---- 
function cbp_gen_section_text() {
	echo "<p>General options for the integration plugin.</p>";
}
function cbp_dim_section_text() {
	echo "<p>Dimension settings</p>";
}

// ---- Text input ---- 
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

// ---- Checkbox ---- 
function cbp_check_input( $arg ) {
	extract($arg);
	
	$disabled = "";
	if ($disable) {
		$disabled = "disabled='disabled'";
	}
	
	if ($value == "on") $checked = "checked='checked'";
	else $checked = "";
	
	$inputField = "<input type='checkbox' name='$name' id='$id' $disabled $checked/>";
	echo "<p>" . $prefix . $inputField . "</p>";
}

// ---- Post type array ---- 
function cbp_posttype_array( $arg ) {
	extract($arg);
	
	$checked = array();
	
	$posttypes = get_post_types(array("public" => true));

	$disabled = "";
	if ($disable) {
		$disabled = "disabled='disabled'";
	}
	
	// Start the list
	echo "<fieldset><ul class=\"cbp-pt-list\">";
	foreach ($posttypes as $key => $pt) {
		if ($value[$key] == "on") $checked[$key] = "checked='checked'";
		else $checked[$key] = "";
		
		echo "	<li class='cbp-pt-entry'>
					<input type='hidden' name='".$name."[$key]' value='off' />
					<label for='cbp-$pt'><input type='checkbox' name='".$name."[$key]' id='cbp-$pt' $disabled $checked[$key] /> " . ucfirst($pt) . "</label>
				</li>";
	}
	echo "</ul></fieldset>";
	
}

// ---- Validates options ---- 
function cbp_validate_options($input) {

	$cbpOptions = get_option("cbp_options");
	$cbpOptions["cbp_post_types"] = array();
	
	// assign on or off according to user input
	foreach($input["cbp_post_types"] as $key => $value) {
		$cbpOptions["cbp_post_types"][$key] = $input["cbp_post_types"][$key];
	}
	
	$cbpOptions["cbp_fh_loc"] = sanitize_text_field($input["cbp_fh_loc"]);
	$cbpOptions["cbp_def_width"] = sanitize_text_field($input["cbp_def_width"]);
	$cbpOptions["cbp_def_height"] = sanitize_text_field($input["cbp_def_height"]);
	$cbpOptions["cbp_chiattach"] = isset($input["cbp_chiattach"]) ? "on" : "off";

	return $cbpOptions;
}

// ---- Renders the front page ----
function cbp_display_front_page() {
	?>
		<h2>Chibipaint Integration</h2>
		<h3>Introduction</h3>
		<p>This plugin's purpose is to integrate the Chibipaint applet with Wordpress. The code was largely rewritten from the previous version back in 2010. Some of the notable differences are:</p>
		<ul class="ul-disc">
		<li>Visibility &amp; Accessibility: Where you had to access the applet via the upload media section, it is now placed in a &quot;metabox&quot; above the WYSIWYG editor of any post type of your choosing</li>
		<li>Choice of location: You can choose where to place the files you save, but you are restricted to within the upload folder.</li>
		</ul>
		<p>If you feel I've missed any, feel free to let me know. The applet itself can be found at http://www.chibipaint.com/.</p>
		<h3>Known Limitations</h3>
		<ul class="ul-disc">
		<li>Currently, you can only change the default dimensions. There are plans to allow users to add dimensions they commonly use and to pick them from a drop down list at the editor.</li>
		<li>There is currently no implementation of templates. The closest thing to it now is to upload an image to the post, select it in the list of images and click on Edit. There are also plans to implement a template feature for the plugin.</li>
		<li>The plugin cannot do a recursive search for wp-config.php.</li>
		<li>This plugin has not been tested with a multiuser wordpress install. It's certain it will not work since there are no filtering based on author, nor is the author of the image saved with the attachment.</li>
		<li>If the user saves the source file, all edits will be based on the source file. This is particularly troublesome for those who decide to save image only later on.</li>
		</ul>
	<?php
}

// ---- Renders the help page ----
function cbp_display_help_page() {
	?>
		<h3>Options Walkthrough</h3>
		<ul class="ul-disc">
		<li><strong>Post type integration</strong>: Which post type do you want to use the applet with? (Default: Post and Pages) </li>
		<li><strong>Save location</strong>: Where within the Wordpress upload folder would you like to save the image and/or source file? Don't forget the trailing slash! (Default: chibi/)</li>
		<li><strong>Add source files to Media Library</strong>: Any source files saved with the image is added to the library. (Default: on)</li>
		<li><strong>Dimensions</strong>: Change the default dimensions shown when creating a new image (Default: 250 x 250)</li>
		</ul>
		<h2>Chibipaint Integration Help</h2>
		<h3>How to use</h3>
		<ol class="ol-decimal">
		<li>Ensure that the post type you wish to use the plugin with is enabled in Chibipaint &gt; Options.</li>
		<li>Create or edit a post. You should find the Chibipaint &quot;metabox&quot; above the text editor as shown: <img src="<?php echo plugins_url('/imgs/2.jpg', __FILE__);?>" /></li>
		<li>Fill in the Name (feel free to use spaces and punctuation) and change the dimension if you wish to work with something other than 250px x 250px.</li>
		<li>Create your drawing. If you find the applet is too crowded, you can set the applet to floating mode. You can find the option in the applet's menu &gt; View &gt; Floating Mode.</li>
		<li>Once you're done, click on the &quot;send pic&quot; button or go to File &gt; Send Oekaki.</li>
		<li>Once it is sent successfully, you should find your drawing listed in the metabox.
			<br/><img src="<?php echo plugins_url('/imgs/6.jpg', __FILE__);?>" /></li>
		<li>That's it! You should be able to find the image in the media library</li>
		</ol>
	<?php
}

// ---- Renders the option page ---- 
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
?>