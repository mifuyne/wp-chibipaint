<?php
// Finding wp-config.php (Solution found on http://wordpress.stackexchange.com/a/76537)
if (isset($_FILES['picture'])) {
	cbp_save();
} else {
	echo 'NO DATA!';
}

function cbp_save() {
	// ---- Page header ---- 
	header('Content-type: text/plain');
	require_once('cbp_common.php');
	// ---- Determine where the upload directory is ----
		// refer to cbp_common

	// ---- grab file and filetype (extension) ---- 
	$file = $_FILES['picture']['name'];
	$ext = (strpos($file, '.') === FALSE) ? '' : substr($file, strrpos($file, '.'));

	// ---- which filename to use depending on circumstances ---- 
	if (!$_GET['edit']) {	// New file
		$filename = strtolower(preg_replace('/[^a-z0-9]+/i', '_', $_GET['name']) . time());
	} else {	// Existing file
		$filename = strtolower(preg_replace('/[^a-z0-9]+/i', '_', $_GET['slug']));
	}
	$uploadfile = $uploaddir . $filename;
	$parentpost = $_GET['post'];
	
	// ---- Save the file(s) and add to library upon success ---- 
	$success = TRUE;
	if (isset($_FILES["chibifile"]))
	  $success = move_uploaded_file($_FILES['chibifile']['tmp_name'], $uploadfile . '.chi');

	$success = move_uploaded_file($_FILES['picture']['tmp_name'], $uploadfile . $ext);
	if ($success) {
		echo "CHIBIOK\n";

		$imgname = $uploaddir . $filename . $ext;
		$wp_filetype = wp_check_filetype(basename($imgname), null );
		
		$attach = array(
			'guid' => $uploadurl . basename($imgname),
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => $_GET['name'],
			'post_name' => $filename,
			'post_content' => '',
			'post_status' => 'inherit'
		);
		
	// ---- Image attaching ----
		require_once(ABSPATH . 'wp-admin/includes/image.php');
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
		
		//  ---- Source file attaching ---- 
		if (isset($_FILES['chibifile']) && $cbpOptions['cbp_chiattach'] == 'on') {
			$chiname = $uploaddir . $filename . '.chi';
			$wp_chitype = wp_check_filetype(basename($chiname), null );
			
			$chiAttach = array(
				'guid' => $uploadurl . basename($chiname),
				'post_mime_type' => 'application/chi',
				'post_title' => $_GET['name'],
				'post_name' => $filename ."-chi",
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
		echo "CHIBIERROR\n";	// Save failed, the app only returns HTML error codes
	}
}
?>