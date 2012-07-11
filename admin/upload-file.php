<?php

// Import WordPress headers to take advantage of the usual helper functions
// This assumes this file is located in a typical /wp-conten/themes/ directory.
include( '../../../../wp-blog-header.php' );

// Upload Security
$upload_security = md5( $_SERVER['SERVER_ADDR'] );
$uploaddir = base64_decode( $_REQUEST['upload_path'] ) . "/";

// If the current user is an administrator, the nonce generated on the front end is correct, and the $_FILES array is set with the security key, go for upload...
if( current_user_can( 'upload_files' ) && wp_verify_nonce( $_GET['_wp_upthemes_admin_upload_nonce'], '_wp_upthemes_admin_upload_nonce' ) && isset( $_FILES[$upload_security] ) ) {

	$file = $_FILES[$upload_security];

	$file = $uploaddir . strtolower( str_replace( '__', '_', str_replace( '#', '_', str_replace( ' ', '_', basename( $file['name'] ) ) ) ) );
	
		if( move_uploaded_file( $_FILES[ $upload_security ]['tmp_name'], $file ) ) {
		
			if( chmod( $file, 0777 ) ) { 
			    echo "success"; 
			} else {
				echo "error" . $_FILES[ $upload_security]['tmp_name'];
			} // end if/else
			
		} else {
		    
		    echo "error" . $_FILES[ $upload_security ]['tmp_name'];
		    
		} // end if

} // end if

?>