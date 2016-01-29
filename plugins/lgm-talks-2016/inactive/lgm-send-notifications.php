<?php
/**
 * A template for Notification-Sending
 */

get_header(); ?>

<div id="primary" class="content-area primary-full">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			// Include the page content template.
			?>
			<p>This is a custom view for debugging</p>
			<?php

		endwhile;
		
		//
		
		$user_query = new WP_User_Query( array( 
			        	// 'include' => array( 14 ),
			        	'orderby' => 'registered',
			        	'order' => 'DESC',
			        	'role' => 'speaker'
			        ) );
			
			if ( ! empty( $user_query->results ) ) {
				foreach ( $user_query->results as $user ) {
					
					// infos about WP_user object
					$user_id = $user->ID ;
					
					echo '<p>Testing User '.$user_id.'</p>';
					
					// Test if speaker has accepted talks!
					
					$the_query = new WP_Query( array(
						  					 	'post_type' => 'talk',
						  					 	'post_status' => 'any',
						  					 	'author' => $user_id,
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
						if ( $the_query->have_posts() ) {
						
							$number_of_talks = $the_query->found_posts;
							
							$the_talk_title = '';
							$all_talk_titles = '';
						
							// Talks Loop
							while ( $the_query->have_posts() ) {
							
								$the_query->the_post();
								
								$the_talk_title = html_entity_decode( get_the_title() );
								
								$all_talk_titles[] = $the_talk_title;
								
							} // end while
							
								// Send this user an email!
													
													$to = $user->user_email;
													$headers[] = 'From: Libre Graphics Meeting <talks@example.org>';
													
													$message = '';
													
													global $wpdb, $wp_hasher;
														$user = get_userdata( $user_id );
													
														// Generate something random for a password reset key.
														$key = wp_generate_password( 20, false );
													
														/** This action is documented in wp-login.php */
														do_action( 'retrieve_password_key', $user->user_login, $key );
													
														// Now insert the key, hashed, into the DB.
														if ( empty( $wp_hasher ) ) {
															require_once ABSPATH . WPINC . '/class-phpass.php';
															$wp_hasher = new PasswordHash( 8, true );
														}
														$hashed = time() . ':' . $wp_hasher->HashPassword( $key );
														$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );
														
														$speaker_first_name = $user->first_name;
														
														if (empty($speaker_first_name)) {

															$message .= "Heya,\r\n\r\n";
															
														} else {
														
															$message .= "Dear ".$speaker_first_name.",\r\n\r\n";
														}
														
														$message .= "We are happy to confirm that your ";
														
														if ( $number_of_talks > 1 ) {
														
															$message .= $number_of_talks." ";
															
															$message .= "proposals – ";
															// loop through array:
															
															$message .= implode(", ", $all_talk_titles);
															
															$message .= " – have been accepted";
															
															$subject = 'Your '.$number_of_talks.' proposals for LGM2016 have been accepted';
														
														} else if ( $number_of_talks == 1 ) {
															
															$message .= "proposal – ".$the_talk_title." – has been accepted";
															
															$subject = 'Your proposal for LGM2016 – '.$the_talk_title.' – has been accepted';
															
														}
														
														$message .= " and will be part of the LGM 2016 program. The meeting takes place from Friday 15th April until Monday 18th April in London.\r\n\r\n";
														
														$message .= "We ask you to confirm by responding to this mail that you are indeed planning to join us in London. Please do so before Thursday February 4 so we can finalize the schedule!\r\n\r\n";
														
														$message .= "You can update your description by using the following link (you will be asked to define your password):\r\n";
													
														$message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . ">\r\n\r\n";
														
														$message .= "If you asked for support from LGM, please take into account that reimbursements happen after LGM, and cover travel costs only (economy class, no accommodation etc.). We can't promise that we will be able to fully cover your travel costs, but are working hard to collect the funds needed. For more information: <http://libregraphicsmeeting.org/2016/attend/reimbursement/>\r\n\r\n";
														
														$message .= "If you need an invitation letter (for obtaining a Visa for example), please read <http://libregraphicsmeeting.org/2016/attend/visas/> and get in touch with us.\r\n\r\n";
														
														$message .= "Latest news, the definitive programme, special events etc. will be published on the LGM website.\r\n\r\n";
														
														$message .= "We look forward to your contribution and to a great LGM!\r\n\r\n";
														
														$message .= "For the LGM team,\r\n\r\n";
														
														$message .= "Phil Langley, Femke Snelting\r\n\r\n";
													
														
														echo '<pre>Message title: '.$subject .'</pre>';
														
														echo '<pre>Message content: '.$message.'</pre>';

//														wp_mail(
//															$user->user_email, // $user->user_email
//															$subject,
//															$message,
//															$headers
//															);

							
					} // end testing if Speaker has accepted Talks
					        		
				} // End foreach
			} // End testing user_query	
		
		?>

	</main><!-- .site-main -->

</div><!-- .content-area -->

<?php get_footer(); 
