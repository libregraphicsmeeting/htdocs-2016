<?php

/*
 * Note: the add_role code needs to run only once.
 * Once the role is created, the code should be commented out.
 
 
 * Capabilities
 *****************
 * The speaker should be able to:
 * - edit his own posts (talk description)
 
 * Sources of information
 *************************
 
 http://shinephp.com/capabilities-set-for-custom-post-type/
 
 http://codex.wordpress.org/Roles_and_Capabilities
 
 http://codex.wordpress.org/Function_Reference/add_role
 
*/

//remove_role( 'speaker' );
//
//add_role('speaker', 'Speaker', array(
//    'edit_posts' => true,  // Allows user to edit their own posts
//    'edit_published_posts' => false,
//    'edit_others_posts' => false, // Allows user to edit others posts not just their own
//    'create_posts' => false, // Allows user to create new posts
//    'delete_posts' => false, // Use false to explicitly deny
//    
//    'edit_talks' => true,  // Allows user to edit their own posts
//    'edit_published_talks' => true,
//    'edit_others_talks' => false, // Allows user to edit others posts not just their own
//    'create_talks' => false, // Allows user to create new posts
//    'delete_talks' => false, // Use false to explicitly deny
//    
//    'read' => true, // Allows access to Administration Panel
//    'upload_files' => true,
//));
//




/* Add Capabilities for "Talk" post type */

//function add_theme_caps() {
//
//    $role = get_role( 'administrator' );
//
//    $role->add_cap( 'edit_talks' ); 
//    $role->add_cap( 'edit_others_talks' ); 
//    $role->add_cap( 'publish_talks' ); 
//    $role->add_cap( 'read_private_talks' ); 
//    $role->add_cap( 'delete_talks' ); 
//    
//    $role->add_cap( 'delete_private_talks' ); 
//    $role->add_cap( 'delete_published_talks' ); 
//    $role->add_cap( 'delete_others_talks' ); 
//    $role->add_cap( 'edit_private_talks' ); 
//    $role->add_cap( 'edit_published_talks' ); 
//    
//}
//add_action( 'admin_init', 'add_theme_caps');


/* Edit screen improvements (for speakers)
******************************/

function remove_media_button_speakers(){
    if ( current_user_can( 'speaker' ) ) {
        remove_action( 'media_buttons', 'media_buttons' );
    }
}
add_action('admin_head', 'remove_media_button_speakers');

function remove_edit_fields() {

if ( current_user_can( 'speaker' ) ) {
			/* Slug meta box. */
		
			/* Custom fields meta box. */
			remove_meta_box( 'postcustom', 'talk', 'normal' );
			
			/* Tags */
			// remove_meta_box( 'tagsdiv-post_tag', 'talk', 'side' );
			
			remove_meta_box( 'mem', 'talk', 'side' );
			
			remove_meta_box( 'talk-statusdiv', 'talk', 'side' );
			remove_meta_box( 'roomdiv', 'talk', 'side' );
			
			remove_meta_box( 'postimagediv', 'talk', 'side' );
	}
}

add_action( 'add_meta_boxes', 'remove_edit_fields' );



/**
 * Remove Menu Pages
 ****************
 * http://codex.wordpress.org/Function_Reference/remove_menu_page
 */


function init_remove_support(){
  if ( current_user_can( 'speaker' ) ) {
    // remove_post_type_support( 'talk', 'editor');
    add_filter( 'user_can_richedit' , '__return_false', 50 );
  }
}
add_action('init', 'init_remove_support',100);


function lgm_remove_menus() {
  
  if ( current_user_can( 'speaker' ) ) {
		  remove_menu_page( 'index.php' );
		  remove_menu_page( 'upload.php' );
		  remove_menu_page( 'edit.php' );
		  remove_menu_page( 'edit-comments.php' );
		  remove_menu_page( 'tools.php' );
  }
}
add_action( 'admin_menu', 'lgm_remove_menus', 999);


/**
 * Remove Items from the Admin Bar
 ****************
 */

function lgm_admin_bar_render() {
    
    if ( current_user_can( 'speaker' ) ) {
	    global $wp_admin_bar;
	    $wp_admin_bar->remove_menu('comments');
	    $wp_admin_bar->remove_menu('new-content');
	  }
}
add_action( 'wp_before_admin_bar_render', 'lgm_admin_bar_render' );

/**
 * Acually, hide the whole Admin Bar
 ****************
 */


function lgm_remove_admin_bar() {
	if ( current_user_can('speaker') && !is_admin() ) {
  	show_admin_bar(false);
	}
}
add_action('after_setup_theme', 'lgm_remove_admin_bar');


/**
 * Remove Talk Status from column view
 ****************
 */

function lgm_manage_columns( $columns ) {
		if ( current_user_can( 'speaker' ) ) {
			// unset($columns['tags']);
			unset($columns['taxonomy-talk-status']);
		}
		return $columns;
}

/**
 * Show only talks that belong to Speaker
 ****************
 */

function lgm_restrict_talk_view( $views ) {
		if ( current_user_can( 'speaker' ) ) {
			// unset($columns['tags']);
			 unset($views['all']);
			 unset($views['draft']);
			 unset($views['pending']);
			 unset($views['publish']);
		}
		return $views;
}

function lgm_column_init() {
  add_filter( 'manage_talk_posts_columns' , 'lgm_manage_columns' );
  add_filter('views_edit-talk', 'lgm_restrict_talk_view');
}
add_action( 'admin_init' , 'lgm_column_init' );


