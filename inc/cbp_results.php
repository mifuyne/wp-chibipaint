<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
<script type='text/javascript' src='http://php.dev/wp/wp-admin/load-scripts.php?c=1&amp;load%5B%5D=jquery,utils,plupload,plupload-html5,plupload-flash,plupload-silverlight,plupload-html4,json2&amp;ver=3.5.1'></script>

<script>
	jQuery(document).ready(function($) {
		$('#iframe-link-test').click(function() {
			// second param describes context (the parent of the iframe)
			$('#cbp-results', window.parent.document).slideUp();
			$('#cbp-canvas', window.parent.document).slideDown();
		});
	});
</script>
	<body>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
// Including the config file to access wordpress api
$directory	= dirname(dirname(dirname(dirname(dirname(__FILE__)))));
require_once("$directory/wp-config.php");

echo "Submission successful! Actually, this page should show the image that was submitted and the option to go back and edit it.";
$args = array(
	'order' => 'ASC',
	'post_mime_type' => '',
	'post_parent' => $_GET['post'],
	'post_status' => null,
	'post_type' => 'attachment'
);
$attaches = get_children( $args );

foreach($attaches as $attachment) {
	echo wp_get_attachment_image($attachment->ID, 'full') ." ";
	// print_r($attachment); // prints the object in human readable form
}
?> <br />
		<input type="button" name="iframe-link-test" id="iframe-link-test" class="button button-primary button-large" value="Show Canvas" />
	</body>
</html>