<?php

/**
 * Prints the option screen for the plugin
 */
function cbp_options() {
    ?>

<div class="wrap">
    <?php screen_icon() ?>
    <h2>Chibipaint for Wordpress</h2>

    <h3 class="title">Dimensions</h3>
    <div>
        <form method="post" action="cbp_options.php">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row">Add Dimension</td>
                        <td>
							<input type="number" size="5" name="width" class="small-text cbp-input-dimension-width" min="1" value="1"/> x 
							<input type="number" size="5" name="height" class="small-text cbp-input-dimension-height" min="1" value="1"/> pixels 
							<input type="button" button class="button action cbp-ajax-action" name="cbp_addDimensions" value="Add" />
							<span class="loading" style="display:none"><img src="<?php echo plugin_dir_url( __FILE__ ) ?>../imgs/musicnote-block.gif" /></span>
						</td>
                    </tr>
                </tbody>
            </table>
        </form>
        <p>Table of Dimensions:</p>

    </div>

    <h3 class="title">File Handling</h3>

    <h3 class="title">Misc</h3>
</div>
<?php
}

function cbp_options_ajax_request() {
	if (isset($_POST['post_var'])) {
		$response = $_POST['post_var'];
		echo $response;
		die();
	}
}
?>
