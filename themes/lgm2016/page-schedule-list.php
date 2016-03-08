<?php
if (is_user_logged_in() || (false === ($custom_query = get_transient('page_schedule_grid')))) {
    $custom_query = new WP_Query(array(
      'post_type' => 'talk',
      'post_status' => 'any',
      'posts_per_page' => -1,
      // 'orderby' => 'date',
      'orderby' => 'meta_value',
      'meta_key' => '_mem_start_date',
      'order' => 'ASC',
    ));
      	     	 	
    set_transient('lgm16_schedule_grid', $custom_query, 12 * HOUR_IN_SECONDS);
} 
?>
<?php include(get_stylesheet_directory().'/header.php') ?>

    	<div id='wrap'>
            <h1>The schedule draft</h1>

            <?php
            if ($custom_query->have_posts()) :
                while( $custom_query->have_posts() ) :
                    $custom_query->the_post();
                    $item = array();
                    /*
                    $item = [
                        "id" => get_the_ID(),
                        "title" => get_the_title(),
                        "firstname" => get_post_meta( $id, 'lgm_speaker_firstname', true ),
                        "lastname" => get_post_meta( $id, 'lgm_speaker_lastname', true ),
                        "day" => $startDay,
                        "time" => $startTime,
                        "duration" => $duration,
                    ];
                    */
            ?>
                <h2><?= get_the_title() ?></h2>
                <?= get_the_content() ?>
                <?php endwhile;
            endif; ?>

    </div>

<?php include(get_stylesheet_directory().'/footer.php') ?>
