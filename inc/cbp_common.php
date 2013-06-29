<?php
// A common file between cbp_save and cbp_results, two scripts executed outside of Wordpress

// Finding wp-config.php (Solution found on http://wordpress.stackexchange.com/a/76537)
function find_wp_config_path() {
	$d = dirname(__FILE__);
	do {
		if( file_exists($d."/wp-config.php") ) {
			return $d;
		}
	} while( $d = realpath("$d/..") );
	return null;
}

$directory = find_wp_config_path();
require_once("$directory/wp-config.php");

// Options
$cbpOptions = get_option("cbp_options");

// Directory and URL
$dir = wp_upload_dir();
if ($cbpOptions["cbp_fh_loc"]) {
	$uploaddir = $dir["basedir"] . "/" . $cbpOptions["cbp_fh_loc"];
	$uploadurl = $dir["baseurl"] . "/" . $cbpOptions["cbp_fh_loc"];	
} else {
	$uploaddir = $dir["path"] . "/";
	$uploadurl = $dir["url"] . "/";
}
?>