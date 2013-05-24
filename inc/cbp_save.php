<?php
/* line 6: Because there doesn't seem to be a better, cleaner way of figuring out 
 * wordpress's root directory in a file that isn't being called by wordpress
 * -_- Also, require_once is slowing down the script :(
 */
$directory	= dirname(dirname(dirname(dirname(dirname(__FILE__)))));
require_once("$directory/wp-config.php");


/* line 13: Since I have no way of determining what goes wrong in this script, I'm saving
 * output of all kinds to a file called log.txt, located in the same directory
 * as this file.
 */
ob_start();

if (isset($_FILES['picture'])) {
	cbp_save($directory);
} else {
	echo "NO DATA!";
}

// TODO: CLEAN UP, try and keep variables with conditional data in if statements while the rest are out
function cbp_save($dir) {
	header('Content-type: text/plain');
	$uploaddir = $dir.'/wp-content/uploads/chibi/';
	$file = $_FILES['picture']['name'];
	$ext = (strpos($file, '.') === FALSE) ? '' : substr($file, strrpos($file, '.'));
	// if $_GET['name'] is set, then change filename to the name value
	if ($_GET['name']) {
		$filename = $_GET['name'];
	} else {
		$filename = date("Y_m_d_U");
	}
	$uploadfile = $uploaddir . $filename;
	$parentpost = $_GET['post'];
	
	$success = TRUE;
	if (isset($_FILES["chibifile"]))
	  $success = move_uploaded_file($_FILES['chibifile']['tmp_name'], $uploadfile . ".chi");

	$success = move_uploaded_file($_FILES['picture']['tmp_name'], $uploadfile . $ext);
	if ($success) {
		echo "CHIBIOK\n";	// might want to move this so that it only says it's successful when the attachment post is made and attached to the post!
		// attaching image to post
		$upload_dir = wp_upload_dir();
		$imgname = $upload_dir['basedir'] . "/chibi/" . $filename . $ext;
		$wp_filetype = wp_check_filetype(basename($imgname), null );
		
		// TODO: Clean this up, use if statements to assign value to variables rather than variables AND actions
		$attach = array(
			'guid' => $upload_dir['baseurl'] . "/chibi/" . basename($imgname),
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => preg_replace('/\.[^.]+$/', '', basename($imgname)),
			'post_content' => '',
			'post_status' => 'inherit'
		);
				
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		if ($_GET['pid']) {
			$attach_id = $_GET['pid'];
			$imgname = get_attached_file($_GET['pid']);
		} else {
			$attach_id = wp_insert_attachment( $attach, $imgname, $parentpost);
		}
		echo "Attach ID: " . $attach_id;
		$attach_data = wp_generate_attachment_metadata( $attach_id, $imgname );
		echo "\nAttach metadata: ";
		print_r($attach_data);
		$attach_results = wp_update_attachment_metadata( $attach_id, $attach_data );
		echo "Update results: " . $attach_results;
		
		// if the user chose to save the image with the source:
		if (isset($_FILES["chibifile"])) {
			// the codes that attaches the file to the post...it shouldn't be any different than the image attachment codes
			$chiname = $upload_dir['basedir'] . "/chibi/" . $filename . ".chi";
			$wp_chitype = wp_check_filetype(basename($chiname), null );
			
			$chiAttach = array(
				'guid' => $upload_dir['baseurl'] . "/chibi/" . basename($chiname),
				'post_mime_type' => 'application/chi',
				'post_title' => preg_replace('/\.[^.]+$/', '', basename($chiname)),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			
			if (!$_GET['pid']) {
				$attach_id = wp_insert_attachment( $chiAttach, $chiname, $parentpost);

				require_once(ABSPATH . 'wp-admin/includes/file.php');
				$attach_data = wp_generate_attachment_metadata( $attach_id, $chiname );
				wp_update_attachment_metadata( $attach_id, $attach_data );
			}
		}
	} else {
		echo "CHIBIERROR\n";
	}
}

$contents = ob_get_flush();
file_put_contents(dirname(__FILE__)."/log.txt", $contents);
?>