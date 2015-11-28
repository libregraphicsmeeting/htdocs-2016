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
//    'edit_posts' => false,  // Allows user to edit their own posts
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
//));





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