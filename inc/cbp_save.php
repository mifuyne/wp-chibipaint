<?php
/* line 6: Because there doesn't seem to be a better, cleaner way of figuring out 
 * wordpress's root directory in a file that isn't being called by wordpress
 * -_- Also, require_once is slowing down the script :(
 */
ob_start();

$directory	= dirname(dirname(dirname(dirname(dirname(__FILE__)))));
require_once("$directory/wp-config.php");

if (isset($_FILES['picture'])) {
	cbp_save();
} else {
	echo "NO DATA!";
}

// TODO: CLEAN UP, try and keep variables with conditional data in if statements while the rest are out
function cbp_save() {
	header('Content-type: text/plain');
	$options = get_option('cbp_options');
	
	$dir = wp_upload_dir();
	if ($options['cbp_fh_loc']) 
		$uploaddir = $dir['basedir'] . '/' . $options['cbp_fh_loc'];
	else 
		$uploaddir = $dir['path'] . '/';
	// echo $uploaddir;
	$file = $_FILES['picture']['name'];
	$ext = (strpos($file, '.') === FALSE) ? '' : substr($file, strrpos($file, '.'));
	// if $_GET['name'] is set, then change filename to the name value
	if (!$_GET['edit']) {
		$filename = preg_replace('/[^a-z0-9]+/i', '_', $_GET['name']) . time();
	} else {
		$filename = $_GET['name'];
	}
	$uploadfile = $uploaddir . $filename;
	$parentpost = $_GET['post'];
	
	$success = TRUE;
	if (isset($_FILES["chibifile"]))
	  $success = move_uploaded_file($_FILES['chibifile']['tmp_name'], $uploadfile . ".chi");

	$success = move_uploaded_file($_FILES['picture']['tmp_name'], $uploadfile . $ext);
	if ($success) {
		echo "CHIBIOK\n";

		// TODO: (06/27/2013) Give option to use wordpress's method of file sorting
		$imgname = $uploaddir . $filename . $ext;
		$wp_filetype = wp_check_filetype(basename($imgname), null );
		
		$attach = array(
			'guid' => $uploaddir . basename($imgname),
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => $_GET['name'],
			'post_name' => $filename,
			'post_content' => '',
			'post_status' => 'inherit'
		);
		
	// ---- Image attaching ----
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		// TODO (06/28/2013): When switching folders, this creates a new file entirely.
		// The title is not the actual title, be sure to fix that in the JS
		// if the attached file matches 
		if (get_attached_file($_GET['pid']) == $imgname) {
			$attach_id = $_GET['pid'];
			$imgname = get_attached_file($_GET['pid']);
		} elseif (get_attached_file($_GET['pid']) != $imgname) {
			global $wpdb;
			$wpdb->update($wpdb->posts, array('post_parent'=>0), array('id'=>$_GET['pid'], 'post_type'=>'attachment'));
			$attach_id = wp_insert_attachment( $attach, $imgname, $parentpost);
		} else {
			$attach_id = wp_insert_attachment( $attach, $imgname, $parentpost);
		}
		$attach_data = wp_generate_attachment_metadata( $attach_id, $imgname );
		$attach_results = wp_update_attachment_metadata( $attach_id, $attach_data );
		
		// TODO (06/28/2013) give user the option to add chi files to the library
		/* if (isset($_FILES["chibifile"])) {
			// the codes that attaches the file to the post...it shouldn't be any different than the image attachment codes
			$chiname = $uploaddir . $filename . ".chi";
			$wp_chitype = wp_check_filetype(basename($chiname), null );
			
			$chiAttach = array(
				'guid' => $uploaddir . basename($chiname),
				'post_mime_type' => 'application/chi',
				'post_title' => $_GET['name'],
				'post_name' => $filename,
				'post_content' => '',
				'post_status' => 'inherit'
			);
			
			if (!$_GET['pid']) {
				$attach_id = wp_insert_attachment( $chiAttach, $chiname, $parentpost);

				require_once(ABSPATH . 'wp-admin/includes/file.php');
				$attach_data = wp_generate_attachment_metadata( $attach_id, $chiname );
				wp_update_attachment_metadata( $attach_id, $attach_data );
			}
		} */

	} else {
		echo "CHIBIERROR\n";
	}
}

$contents = ob_get_flush();
file_put_contents(dirname(__FILE__)."/log.txt", $contents);
?>