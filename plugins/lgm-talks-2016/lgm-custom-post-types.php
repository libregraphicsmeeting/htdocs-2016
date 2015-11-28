<?php

/* Register Post Types
 ********************
*/

add_action( 'init', 'bcf_register_post_types' );

function bcf_register_post_types() {

		register_post_type(
				'talk', array(	
					'label' => __( 'Talks' ),
					'public' => true,
					'show_ui' => true,
					'show_in_menu' => true,
					 'menu_icon' => 'dashicons-megaphone', // src: http://melchoyce.github.io/dashicons/
					// dashicons-admin-post
					'capability_type' => 'talk',
					'map_meta_cap' => true,
					'hierarchical' => false,
					'has_archive'		 => false,
					'rewrite' => array('slug' => ''),
					'query_var' => true,
					'exclude_from_search' => false,
					'menu_position' => 6,
					'supports' => array(
						'title',
						'editor',
						'revisions',
						'thumbnail',
						'author',
						'custom-fields',
						'comments'
						),
					'taxonomies' => array( 'talk-status' ),
					'labels' => array (
				  	  'name' => 'Talks',
				  	  'singular_name' => 'Talk',
				  	  'menu_name' => 'Talks',
				  	  'add_new' => 'Add',
				  	  'add_new_item' => 'Add a talk',
				  	  'edit' => 'Edit',
				  	  'edit_item' => 'Edit the talk',
				  	  'new_item' => 'New talk',
				  	  'view' => 'View',
				  	  'view_item' => 'View talk',
				  	  'search_items' => 'Search',
				  	  'not_found' => 'No result',
				  	  'not_found_in_trash' => 'No result',
				  	  'parent' => 'Parent element',
				),
			) 
		);
		
			
		
	// Add a Language Taxonomy
	
				
				register_taxonomy('talk-status',
						array( 'speaker' ),
						array( 
				 		'hierarchical' => true, 
				 		'label' => 'Talk Status',
				 		'labels'  => array(
				 			'name'                => _x( 'Talk Status', 'taxonomy general name' ),
				 			'singular_name'       => _x( 'Talk Status', 'taxonomy singular name' ),
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
				 		'show_ui' => true,
				 		'query_var' => true,
				 		'rewrite' => array('slug' => 'talk-status'),
				 		'singular_label' => 'Talk Status') 
				 );	
		
		

}

