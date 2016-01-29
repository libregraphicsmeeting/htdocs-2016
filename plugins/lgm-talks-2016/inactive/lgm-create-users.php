<?php
/**
 * A Page template that creates new users
 */

get_header(); ?>

<div id="primary" class="content-area primary-full">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			// Include the page content template.
			?>
			<pre>
			
				This is a custom view for creating new users.
				
			 	The method:
			
				- We query for accepted talks.
				- We get the speaker information (name, email).
				- We test if this speaker already exists.
				- If yes: set author of talk.
				- If not: create new Speaker.
			
			</pre>
			<?php

		endwhile;
		
		// 1) Query for accepted talks
		
		
		$the_query = new WP_Query( array(
			  					 	'post_type' => 'talk',
			  					 	'post_status' => 'any',
			  					 	'tax_query' => array(
			  					 			array(
			  					 				'taxonomy' => 'talk-status',
			  					 				'field'    => 'slug',
			  					 				'terms'    => 'accepted',
			  					 			),
			  					 		),
			  					 	'posts_per_page' => -1,
			  					 	'orderby' => 'date',
			  					 	'order' => 'DESC',
			  					 )  );
			
			
			// The Loop
			if ( $the_query->have_posts() ) {
				
				while ( $the_query->have_posts() ) {
					
					$the_query->the_post();
					
					$talk_ID = get_the_ID();
					
					$speaker_id = '';
					
					$speaker_email = get_post_meta( $talk_ID, 'lgm_speaker_email', true );
					
					$speaker_firstname = get_post_meta( $talk_ID, 'lgm_speaker_firstname', true );
					$speaker_name = $speaker_firstname;
					
					$speaker_lastname = get_post_meta( $talk_ID, 'lgm_speaker_lastname', true );
					if ( !empty($speaker_lastname) ) {
						// append lastname
						$speaker_name .= ' '.$speaker_lastname; 
					}
					
					$speaker_website = get_post_meta( $talk_ID, 'lgm_speaker_website', true );
					

					// NOW: test if author with username $speaker_email exists.
					
					$user = get_user_by( 'login', $speaker_email );
					
					if ( !empty($user) ) { 
						
							// yes: set author
							
							// echo 'User already exists: ' . $user->first_name . ' ' . $user->last_name . ' ' . $user->user_email . "\r\n";
							
							$speaker_id = $user->ID;
							
					} else {
					
						// ELSE
						// create the user
						// echo 'we need to create user for: ' . $speaker_email . "\r\n";
						
						// Method:
						// https://codex.wordpress.org/Function_Reference/wp_insert_user
						
						$userdata = array(
						    'user_login'  =>  $speaker_email,
						    'user_email'  =>  $speaker_email,
						    'display_name'  =>  $speaker_name,
						    'first_name'  =>  $speaker_firstname,
						    'last_name'  =>  $speaker_lastname,
						    'user_url'    =>  $speaker_website,
						    'user_pass'   =>  NULL,  // When creating an user, `user_pass` is expected.
						    'role'  =>  'speaker',
						);
						
						$user_id = wp_insert_user( $userdata ) ;
						
						//On success
						if ( ! is_wp_error( $user_id ) ) {
						    echo "User created : ". $user_id . "\r\n";
						    $speaker_id = $user_id;
						}
						
					} // end else
					
					if (!empty($speaker_id)) {
					
						// define Author of talk
						
						$arg = array(
						    'ID' => $talk_ID,
						    'post_author' => $speaker_id
						);
						wp_update_post( $arg );
					
					}
						
						
					} // end while
					
			
				} // end if
		
		
		?>

	</main><!-- .site-main -->

	<?php // get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_footer(); 
