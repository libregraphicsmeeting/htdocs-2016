<?php
/**
 * The template for the Submissions-Review page
 *
 */

get_header(); ?>

<style>
        .pending-list-item[data-state="pending"] {
            color:inherit;
        }
        .pending-list-item[data-state="rejected"] {
            color: red;
        }
        .pending-list-item[data-state="accepted"] {
            color: green;
        }
</style>

<div id="primary" class="content-area primary-full">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			// Include the page content template.
			?>
			<p>This is a custom view for handling of talk submissions</p>
			<?php

		endwhile;
		
		// Load all talks: 
		
		$custom_query = new WP_Query( array(
		  					 	'post_type' => 'talk',
		  					 	'post_status' => 'any',
		  					 	'posts_per_page' => -1,
		  					 	'orderby' => 'date',
		  					 	'order' => 'ASC',
		  					 ) ); 
		  					 	
		 if ($custom_query->have_posts()) : 
		 		
		 			$metronom = 1;
		 		
		 		?>
		 			<section class="talk-list pending-form">
		 			<table class="table table-hover table-bordered table-condensed pending-list">
		 				<thead>
		 					<tr>
		 						<th class="row-nr">#</th>
		 						<th class="row-name">Name / Title</th>
		 				    <th>Abstract</th>
		 				    <th class="row-actions">Actions</th>
		 					</tr>
		 				</thead>
		 				<tbody>
		 			<?php
		 			
		 			
					while( $custom_query->have_posts() ) : $custom_query->the_post();
							
							?>
							<tr class="pending-list-item" data-state="0" data-id="<?php echo get_the_ID(); ?>">
								<th><?php echo $metronom++; ?></th>
								<?php 
										// 1) Name (custom field)
										echo '<td>';
										echo get_post_meta( get_the_ID(), 'lgm_speaker_firstname', true ).' ';
										echo get_post_meta( get_the_ID(), 'lgm_speaker_lastname', true );
										echo ' : <br/>';
										
										// 2) Title
										echo '<i>';
										the_title( '', '</i></th>');
										
										// 3) Abstract
										echo '<td>';
										the_excerpt();
										echo '</td>';
										
										// 4) Actions
										echo '<td>';
							       // echo '<a class="action pending-accept">accept</a>';
							       // echo '<a class="action pending-reject">reject</a>'; 
										echo '</td>';
								
							echo '</tr>';
							
					endwhile; 
					echo '</tbody></table>';
					?></section><?php
		endif;
		wp_reset_postdata();
		
		?>

	</main><!-- .site-main -->

	<?php // get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<script>
jQuery(document).ready(function($){	
				
        (function ($) {
						
						var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
						
            var $form = $('.pending-form'),
                $item = $form.find( '.pending-list-item');

            // Trigger to make AJAX call to set state for ID
            // ( 1:accept, -1:reject )
            function setState(id, state) {

                // item clicked
                var $item = $('.pending-list-item[data-id="' + id + '"]'),

                // gather data
                    data = {
                        action: 'set_pending_item_state',
                        id:      id,
                        state:   state
                    };

                // make AJAX POST call    
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: data,
                    success: function (response) {

                        // look at the response

                        if (response.success) {

                            // update the UI to reflect the response
                            $item.attr ('data-state', state);

                            // succcess data
                            console.log(response.data);

                        } else {

                            // no good
                            console.log(response);
                        }
                    }
                });
            }

            // setup the items
            $item.each (function (inx, item){

                var $item = jQuery(item),
                    $acceptBtn = $item.find ('.pending-accept'),
                    $rejectBtn = $item.find ('.pending-reject');

                // setup the button click handlers

                $acceptBtn.on ('click', function(){
                    var id = $item.attr ('data-id');
                    setState( id, 'accepted');
                });

                $rejectBtn.on ('click', function(){
                    var id = $item.attr ('data-id');
                    setState( id, 'rejected');
                });
            });

        })(jQuery);
        
 });
 </script>
<?php get_footer(); ?>
