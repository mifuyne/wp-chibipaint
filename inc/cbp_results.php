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
require_once("cbp_common.php");


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
	if (file_exists($uploaddir.$attachment->post_name . ".chi")) {
			$painting = "<input type=\"hidden\" name=\"chifile\" id=\"cbp-chi-". $attachment->ID ."\" value=\"".$uploadurl.$attachment->post_name.".chi\" />";
	} else $painting = "";	
	// if ($attachment->post_mime_type == 'image/png') {
		$imgurl = wp_get_attachment_image_src($attachment->ID, 'full');
				?>
				<li>
					<input type="hidden" name="slug" id="cbp-slug-<?php echo $attachment->ID; ?>" value="<?php echo $attachment->post_name; ?>" />
					<input type="hidden" name="title" id="cbp-title-<?php echo $attachment->ID; ?>" value="<?php echo $attachment->post_title; ?>" />
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