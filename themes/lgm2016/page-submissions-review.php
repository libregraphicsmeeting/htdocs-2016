<?php
/**
 * The template for the Submissions-Review page
 *
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			// Include the page content template.
			?>
			<p>Here will be a custom view for handling of talk submissions</p>
			<?php

			// If comments are open or we have at least one comment, load up the comment template.
			

			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
