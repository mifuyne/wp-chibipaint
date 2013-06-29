<?php
// Renders the Chibipaint canvas
function cbp_canvas(){

	$cbpOptions = get_option('cbp_options');
	// ---- Checking for attachments ----
	$kidArgs = array(
 	"post_mime_type" => "image",
	"post_parent" => $_GET["post"],
	"post_type" => "attachment"
	);
	$kids = get_children( $kidArgs );
	
	// ---- Rendering the canvas ----
?>
    <div class="cbp-editor-container postbox">
		<div class="handlediv"><br /></div><span><h3>Chibipaint</h3></span>
		<div id="cbp-results" class="inside"></div>
		<iframe id="cbp-iframe-results" seamless <?php if ($_GET['action'] == 'edit' && !empty($kids)) { echo 'src="../wp-content/plugins/wp-chibipaint/inc/cbp_results.php?post=' . $_GET['post'] . '"'; } ?>></iframe> <!-- http://www.sitepoint.com/forums/showthread.php?231385-Target-a-link-to-a-Div-tag -->
		<div id="cbp-canvas-container" class="inside">
			<div id="cbp-editor-options" <?php if ($_GET['action'] == 'edit' && !empty($kids)) echo 'style="display: none;"' ?>>
				<div id="cbp-editor-dimensions">
					<h4>Dimensions</h4>
					<input type="hidden" name="cbp-curr-page" id="cbp-curr-page" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
					<input type="hidden" name="cbp-post-id" id="cbp-post-id" value="<?php echo get_the_ID(); ?>" />
					<label for="cbp-name">Name: </label><input type="text" name="cbp-name" id="cbp-name" class="regular-text cbp-input-dimension-width" /><br />
					<label for="cbp-canvas-width">W: </label><input type="number"  name="cbp-canvas-width" id="cbp-canvas-width" class="small-text cbp-input-dimension-width" min="1" value="<?php echo $cbpOptions['cbp_def_width']; ?>"/> x
					<label for="cbp-canvas-height">H: </label><input type="number" name="cbp-canvas-height" id="cbp-canvas-height" class="small-text cbp-input-dimension-height" min="1" value="<?php echo $cbpOptions['cbp_def_height']; ?>"/> pixels 
				</div>
				<div id="cbp-editor-action">
					<input type="button" name="cbp-canvas-start" id="cbp-canvas-start" class="button button-primary button-large" value="Start Chibipaint" />
				</div>
			</div>
			<div id="cbp-canvas" style="display: none;">
				
			</div>
		</div>
    </div>
<?php
}
?>
