<?php


function lgm_register_styles() {

	/**
	 * Custom CSS
	 */

	if ( WP_DEBUG == true ) {
	
			// In DEV mode: load the MAIN stylesheet - uncompressed
			
			wp_enqueue_style( 
					'main-style', 
					get_stylesheet_directory_uri() . '/css/dev/00-main.css', // main.css
					false, // dependencies
					null // version
			); 
	
	} else {
	
			// In PRODUCTION mode: load the MAIN stylesheet - combined and minified
			wp_enqueue_style( 
					'main-style', 
					get_stylesheet_directory_uri() . '/css/build/styles.20141211230812.css', // main.css
					false, // dependencies
					null // version
			); 
	}
		
		/* Remove uneccessary fonts loaded by parent theme */
		
		wp_dequeue_style( 'twentysixteen-style' );
		wp_deregister_style( 'twentysixteen-style' );
		
		wp_dequeue_style( 'twentysixteen-fonts' );
		wp_deregister_style( 'twentysixteen-fonts' );

}
add_action( 'wp_enqueue_scripts', 'lgm_register_styles', 25 );

// apply_filters( 'comment_form_default_fields', $fields );
// see https://core.trac.wordpress.org/browser/tags/4.4.2/src/wp-includes/comment-template.php#L2158

function lgm_alter_comment_form_fields($fields){
    //$fields['author'] = ''; //removes name field
    //$fields['email'] = '';  //removes email field
    //$fields['url'] = '';  //removes website field
    $fields['title_reply'] = 'Leave a comment'; 
    return $fields;
}
add_filter('comment_form_default_fields','lgm_alter_comment_form_fields');
add_filter('comment_form_defaults','lgm_alter_comment_form_fields');


// Change-Detector-XXXX - for automatic synching