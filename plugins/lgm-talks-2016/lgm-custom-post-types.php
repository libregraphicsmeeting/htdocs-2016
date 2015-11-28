<?php

/* Register Post Types
 ********************
*/

// Add a "Talk" post type

function lgm_register_post_types() {

		$args = array(
		  'public' => true, // Implies exclude_from_search: false, publicly_queryable, show_in_nav_menus, show_ui, show_in_menu
		  'label'  => 'Talks',
		  'labels' => array(
		  		'name'               => 'Talks',
		  		'singular_name'      => 'Talk',
		  		'menu_name'          => 'Talks',
		  		'name_admin_bar'     => 'Talk',
		  		'add_new'            => 'Add New',
		  		'add_new_item'       => 'Add New Talk',
		  		'new_item'           => __( 'New Talk'),
		  		'edit_item'          => __( 'Edit Talk'),
		  		'view_item'          => __( 'View Talk'),
		  		'all_items'          => __( 'All Talks'),
		  		'search_items'       => __( 'Search Talks'),
		  		'parent_item_colon'  => __( 'Parent Talks:'),
		  		'not_found'          => __( 'No talks found.'),
		  		'not_found_in_trash' => __( 'No talks found in Trash.')
		  	),
		  'menu_icon' => 'dashicons-megaphone',
		  'menu_position' => 20,
		  'supports' => array(
		  	'title',
		  	'editor',
		  	'revisions',
		  	'thumbnail',
		  	'author',
		  	'custom-fields',
		  	'publicize',
		  	),
		  	'taxonomies' => array( 'talk-status', 'post_tag' ),
		  	'has_archive'		 => true,
		);
		register_post_type( 'talk', $args );
		
			
// Add a "Talk Status" taxonomy

		register_taxonomy( 
					'talk-status',
					array( 'talk' ), // = $object_type
					array( 
			 		'hierarchical' => true, 
			 		'label' => 'Talk Status',
			 		'labels'  => array(
			 			'name'                => 'Talk Status',
			 			'singular_name'       => 'Talk Status',
			 			'search_items'        => __( 'Search' ),
			 			'popular_items'              => __( 'Most used' ),
			 					'all_items'                  => __( 'All' ),
			 					'parent_item'                => null,
			 					'parent_item_colon'          => null,
			 					'edit_item'                  => __( 'Edit Talk Status' ),
			 					'update_item'                => __( 'Update Talk Status' ),
			 					'add_new_item'               => __( 'New Talk Status' ),
			 					'new_item_name'              => __( 'New Talk Status' ),
			 					'separate_items_with_commas' => __( 'Separage with commas' ),
			 					'add_or_remove_items'        => __( 'Add or remove' ),
			 					'choose_from_most_used'      => __( 'Choose from most used' ),
			 					'not_found'                  => __( 'No Talk Status found' ),
			 			'menu_name'           => __( 'Talk Status' )
			 		),
			 		'public' => true,
			 		'show_admin_column' => true,
			 		'singular_label' => 'Talk Status'
			 		) 
		);	
	
}

add_action( 'init', 'lgm_register_post_types' );	