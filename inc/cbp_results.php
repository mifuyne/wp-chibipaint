<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	<body>
		<form>
<?php
// TODO: Clean up and remove redundancies (for example, having two arrays that are almost identical

// Including the config file to access wordpress api
$directory	= dirname(dirname(dirname(dirname(dirname(__FILE__)))));
require_once("$directory/wp-config.php");

$imgArgs = array(
	'order' => 'ASC',
 	'post_mime_type' => 'image',
	'post_parent' => $_GET['post'],
	'post_status' => null,
	'post_type' => 'attachment'
);
$imgAttaches = get_children( $imgArgs );

$chiArgs = array(
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
	foreach ($chiAttaches as $chifile) {
		if ($chifile->post_title == $attachment->post_title)
			$chi = $chifile->guid;
	}
	if ($attachment->post_mime_type == 'image/png') {
		$imgurl = wp_get_attachment_image_src($attachment->ID, 'full');
				?>
			<div class="cbp-thumbnail">
				<input type="hidden" name="name" id="cbp-name" value="<?php echo $attachment->post_name; ?>" />
				<input type="hidden" name="pngfile" id="cbp-pngfile" value="<?php echo $imgurl[0]; ?>" />
				<label for="<?php echo $attachment->ID; ?>"><?php echo wp_get_attachment_image($attachment->ID, 'thumbnail') ." "; ?></label><br />
				<input type="radio" name="paintings" id="<?php echo $attachment->ID; ?>" value="<?php echo $chi; ?>" />
				<label for="<?php echo $attachment->ID; ?>" class="cbp-title" style="max-width: 125px; word-wrap: break-word; display: inline-block;">
					<?php echo $attachment->ID . ". <span>" . $attachment->post_title ."</span>"; ?>
				</label>
			</div>
<?php 
	}
}
?>			<br />
			<input type="button" name="edit-painting" id="edit-painting" class="button button-primary button-large" value="Show Canvas" />
		</form>
	</body>
</html>