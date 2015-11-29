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



// Change-Detector-XXXX - for automatic synching