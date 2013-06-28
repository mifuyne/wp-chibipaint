<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	<body>
		<form>
			<ul class="cbp-thumbnail">
<?php
// TODO: Clean up and remove redundancies (for example, having two arrays that are almost identical

// Including the config file to access wordpress api
$directory	= dirname(dirname(dirname(dirname(dirname(__FILE__)))));
require_once("$directory/wp-config.php");

// check for existing chi file instead of adding it to the media library?
$options = get_option('cbp_options');

$dir = wp_upload_dir();
if ($options['cbp_fh_loc']) {
	$uploaddir = $dir['basedir'] . '/' . $options['cbp_fh_loc'];
	$uploadurl = $dir['baseurl'] . '/' . $options['cbp_fh_loc'];	
} else {
	$uploaddir = $dir['path'] . '/';
	$uploadurl = $dir['url'] . '/';
}

$imgArgs = array(
	'order' => 'ASC',
 	'post_mime_type' => 'image',
	'post_parent' => $_GET['post'],
	'post_status' => null,
	'post_type' => 'attachment'
);
$imgAttaches = get_children( $imgArgs );

/* $chiArgs = array(
	'order' => 'ASC',
	'post_mime_type' => 'application/chi',
	'post_parent' => $_GET['post'],
	'post_status' => null,
	'post_type' => 'attachment'
);
$chiAttaches = get_children( $chiArgs );

/* foreach ($chiAttaches as $chifile) {
	$chi[] = $chifile->guid;	// Try searching an unfiltered children query for chi files and match by post title
} */
foreach($imgAttaches as $attachment) {
	// print_r($attachment); // prints the object in human readable form
	// Check if the chi file for this attachment exists, if it does, create a hidden input for it
	// TODO (06/28/2013) If user choose to add chi as attachment, change the way the script looks for it (check and see if either way is faster)
	if (file_exists($uploaddir.$attachment->post_name . ".chi")) {
		// echo "Chi File exists for " . $attachment->post_name . "!";
		$painting = "<input type=\"hidden\" name=\"chifile\" id=\"cbp-chi-". $attachment->ID ."\" value=\"".$uploadurl.$attachment->post_name.".chi\" />";
	} else $painting = ""; 
	
	// if ($attachment->post_mime_type == 'image/png') {
		$imgurl = wp_get_attachment_image_src($attachment->ID, 'full');
				?>
				<li>
					<input type="hidden" name="name" id="cbp-name-<?php echo $attachment->ID; ?>" value="<?php echo $attachment->post_name; ?>" />
					<input type="hidden" name="pngfile" id="cbp-png-<?php echo $attachment->ID; ?>" value="<?php echo $imgurl[0]; ?>" />
					<?php echo $painting; ?>
					<label for="<?php echo $attachment->ID; ?>"><?php echo wp_get_attachment_image($attachment->ID, 'thumbnail') ." "; ?></label><br />
					<input type="radio" name="paintings" id="<?php echo $attachment->ID; ?>" value="<?php echo $attachment->ID; ?>" />
					<label for="<?php echo $attachment->ID; ?>" class="cbp-title" style="max-width: 125px; word-wrap: break-word; display: inline-block;">
						<?php echo $attachment->post_title; ?>
					</label>
				</li>
<?php 
	// }
}
?>			
			</ul>
			<br />
			<input type="button" name="edit-painting" id="cbp-edit-painting" class="button button-primary button-large" value="Edit Image" /> | 
			<input type="button" name="cbp-canvas-start" id="cbp-start-new" class="button button-primary button-large" value="New Image" />
		</form>
	</body>
</html>