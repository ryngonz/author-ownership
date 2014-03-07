<?php
require('../../../../wp-load.php');

$user_id = stripslashes($_GET['uid']);
$post_id = stripslashes($_GET['post_id']);

if ( $user_id && !empty( $user_id ) ) {
	if ( $post_id && !empty( $post_id ) ) {
		$args = array( 'post_type' => 'any');
		$ao_post = get_post( $post_id );
		
		if ( $ao_post->post_author != $user_id ) {
		
			$my_post = array(
		    	'ID'           => $post_id,
		    	'post_author'  => $user_id
		 	);
			// Update the post into the database
	  		wp_update_post( $my_post );
	  		wp_redirect( get_permalink( $post_id ) );
		}
		else {
			wp_redirect( home_url() );
			exit;
		}
	}
	else {
		wp_redirect( home_url() );
		exit;
	}
}
else {
	wp_redirect( home_url() );
	exit;
}
?>