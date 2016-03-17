<?php
/**
 * The template part for displaying single posts
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php 
		
		$lgm_current_posttype = get_post_type();
		$id = get_the_ID();
		 
		if ( $lgm_current_posttype == 'talk' ) {
		    
		    ?>
		    <header class="entry-header">
		    	<h1 class="entry-title">
		    	<?php 
		    		// Show the speaker name
		    		echo '<span class="talk-title-speakers">';
		    		
		    		echo get_post_meta( $id, 'lgm_speaker_firstname', true );
		    		echo ' ';
		    		echo get_post_meta( $id, 'lgm_speaker_lastname', true );
		    		
		    		// Show additional speakers:
		    		
		    		$lgm_additional_speakers = get_post_meta( $id, 'lgm_additional_speakers', true );
		    		
		    		if (!empty($lgm_additional_speakers)) {
		    		
		    			echo ', '. $lgm_additional_speakers ;
		    		
		    		}
		    	
		    		echo ' :</span><br/>';	
		    	 	echo '<span class="talk-title">';
		    	  the_title(); 
		    	  echo '</span>';
		    	  
		    	  ?>
		    	</h1>
		    </header><!-- .entry-header -->
		    <?php
		    
		} else {
		
				?>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->
				<?php
		
		}
	
	
	 ?>

	

	<?php twentysixteen_excerpt(); ?>

	<?php twentysixteen_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
			the_content();


			// Show speaker bio
			
			$lgm_speaker_bio = get_post_meta( $id, 'lgm_short_bio', true );
			
			if (!empty($lgm_speaker_bio)) {
			
				echo '<p class="biography">'. $lgm_speaker_bio . '</p>' ;
			
			}
			
			// Show speaker website
			
			$lgm_speaker_website = get_post_meta( $id, 'lgm_speaker_website', true );
			
			if (!empty($lgm_speaker_website)) {
			
				echo '<p class="website">On the web: <a href="'.$lgm_speaker_website.'">'. $lgm_speaker_website . '</a></p>' ;
			
			}
			


			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );

			if ( '' !== get_the_author_meta( 'description' ) ) {
				get_template_part( 'template-parts/biography' );
			}
		?>
	</div><!-- .entry-content -->

	
</article><!-- #post-## -->
