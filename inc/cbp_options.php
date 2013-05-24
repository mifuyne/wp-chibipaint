<?php
/**
 * Register options
 */
function cbp_options_init() {
	
	/* TODO:	Create options
	 *			Canvas Dimension options should be added via AJAX (figure out how)
	 *			-- templates -> future feature
	 *			
	 */
	// Create the option page(s)
    if(function_exists('add_options_page')) {
		add_options_page('ChibiPaint', 'Chibipaint', 'manage_options', 'chibipaint', 'cbp_options');
    }
	
	// option registrations
	register_setting('chibipaint_options', 'chibipaint_options', 'cbp_options_validate');
	
	// Dimension Options
	add_settings_section('cbp_options_dimensions', 'Canvas Dimensions', 'cbp_options_dimensions', 'chibipaint');
	add_settings_field('cbp_opt_width_int', 'Width', 'cbp_options_int', 'chibipaint', 'cbp_options_dimensions');
	
	// File Handling
	add_settings_section('plugin_filing', 'File Handling', 'cbp_options_filing', 'chibipaint');
}

function cbp_options_validate($input) {
	// Input validation codes here
}

/**
 * Prints the option screen for the plugin
 */
function cbp_options() {
    ?>

<div class="wrap">
    <?php screen_icon() ?>
    <h2>Chibipaint for Wordpress</h2>

    <h3 class="title">Dimensions</h3>
	<form method="post" action="cbp_options.php">
		<?php
		settings_fields('chibipaint_options');
		do_settings_sections('chibipaint');
		?>
		<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" class="button button-primary"/>
	</form>
</div>
<?php
}

function cbp_options_dimensions() {

}

function cbp_options_filing() {
	echo "<p>File Handling options here</p>";
}

// Form fields
function cbp_options_int() {
	$options = get_option('plugin_options');
	echo "<input id='cbp_opt_width_int' name='chibipaint_options[text_string]' size='25' type='text' value='{$options['text_string']}' />";
}

function cbp_options_ajax_request() {
	if (isset($_POST['post_var'])) {
		$response = $_POST['post_var'];
		echo $response;
		die();
	}
}
?>
