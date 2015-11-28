<?php
/*
Plugin Name: LGM Sanitize Settings 2016
Plugin URI: 
Description: This plugin adds some useful basic settings.
Version: 1.0.0
Author: LGM Infrastructure Team
Author URI: https://github.com/libregraphicsmeeting/infrastructure/
*/


/* Allow Automatic Updates
 ******************************
 * http://codex.wordpress.org/Configuring_Automatic_Background_Updates
 */

add_filter( 'auto_update_plugin', '__return_true' );
add_filter( 'auto_update_theme', '__return_true' );
// add_filter( 'allow_major_auto_core_updates', '__return_true' );



/* Allowed FileTypes
 ********************
 * method based on 
 * http://howto.blbosti.com/?p=329
 * List of defaults: https://core.trac.wordpress.org/browser/tags/3.8.1/src/wp-includes/functions.php#L1948
*/

add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes ( $existing_mimes=array() ) {

		// add your extension to the array
		$existing_mimes['svg'] = 'image/svg+xml';
		$existing_mimes['epub'] = 'application/epub+zip';

		// removing existing file types
		unset( $existing_mimes['bmp'] );
		unset( $existing_mimes['tif|tiff'] );

		// and return the new full result
		return $existing_mimes;
}




/*
 * File Upload Security
 
 * Sources: 
 * http://www.geekpress.fr/wordpress/astuce/suppression-accents-media-1903/
 * https://gist.github.com/herewithme/7704370
 
 * See also Ticket #22363
 * https://core.trac.wordpress.org/ticket/22363
 * and #24661 - remove_accents is not removing combining accents
 * https://core.trac.wordpress.org/ticket/24661
*/ 

add_filter( 'sanitize_file_name', 'remove_accents', 10, 1 );
add_filter( 'sanitize_file_name_chars', 'sanitize_file_name_chars', 10, 1 );
 
function sanitize_file_name_chars( $special_chars = array() ) {
	$special_chars = array_merge( array( '’', '‘', '“', '”', '«', '»', '‹', '›', '—', 'æ', 'œ', '€','é','à','ç','ä','ö','ü','ï','û','ô','è' ), $special_chars );
	return $special_chars;
}



/* Dashboard improvement
******************************/

// http://wp.tutsplus.com/tutorials/customizing-wordpress-for-your-clients/
// http://www.wpbeginner.com/wp-tutorials/how-to-remove-wordpress-dashboard-widgets/

function lgm_remove_dashboard_widgets() {
	// Globalize the metaboxes array, this holds all the widgets for wp-admin
	global $wp_meta_boxes;
	
//	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
//	
//	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );
//
//	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity'] );

	// RSS feeds:
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );

}
add_action( 'wp_dashboard_setup', 'lgm_remove_dashboard_widgets' );



/* login interface
******************************/

//custom Login
function custom_login() {
	?>
	<style>
	
	
	</style>
	<?php
}

add_action( 'login_head', 'custom_login' );

// src: http://codex.wordpress.org/Customizing_the_Login_Form

// change the link values so the logo links to your WordPress site
function my_login_logo_url() {
	return get_bloginfo( 'url' );
}

add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
	return 'Libre Graphics Meeting';
}

add_filter( 'login_headertitle', 'my_login_logo_url_title' );
  
  
  