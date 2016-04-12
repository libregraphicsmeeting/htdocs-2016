<?php
/**
 * Template for building the schedule
 */

$pageTitle = get_the_title();

get_header();

$time = [
    '10:00',
    '11:00',
    '12:00',
    '13:00',
    '14:00',
    '15:00',
    '16:00',
    '17:00',
    '18:00',
    '19:00',
];

$day = [15, 16, 17, 18];

$time_slot = [
    15 => [
        11*60+0,
        13*60+20,
        16*60+20,
    ],
    16 => [
        10*60+00,
        13*60+00,
        15*60+40,
        16*60+40,
        18*60+00,
        19*60+00,
    ],
    17 => [
        10*60+00,
        13*60+00,
        15*60+40,
        18*60+00,
    ],
    18 => [
        09*60+00,
        10*60+00,
        13*60+00,
        15*60+20,
    ],
];

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

$schedule = [
];

foreach($day as $d_day) {
    $item = [];
    foreach ($time_slot[$d_day] as $d_slot) {
        $item[] = [];
    }
    $schedule[$d_day] = $item;
}
// echo("<pre>".print_r($schedule, 1)."</pre>");

// echo("<pre>".print_r($time_slot, 1)."</pre>");
function getScheduleSlot($day, $time) {
    global $time_slot;
    $result = 0;
    list($h, $m) = explode(':', $time);
    $t = ($h * 60) + $m;

    // echo("<pre>".print_r($t, 1)."</pre>");
    $result = count(array_filter($time_slot[$day], function($v) use($t) {return $v <= $t;})) - 1;
    return $result;
}

if ($custom_query->have_posts()) {

    while( $custom_query->have_posts() ) {
        $custom_query->the_post();
        $item = array();
        $startMeta = get_post_meta( $id, '_mem_start_date', true);

        if ($startMeta) {
            $startDate = new DateTime($startMeta);
            $startTime = $startDate->format('H:i');
            $startDay = $startDate->format('d');
            $endMeta = get_post_meta( $id, '_mem_end_date', true);
            $duration = 20;
            if ($endMeta) {
                $endDate = new DateTime($endMeta);
                $diff = $startDate->diff($endDate);
                // echo("<pre>".print_r($diff, 1)."</pre>");
                $duration = ($diff->h * 60) + $diff->i;
            }
            $item = [
                "id" => get_the_ID(),
                "title" => get_the_title(),
                "firstname" => get_post_meta( $id, 'lgm_speaker_firstname', true ),
                "lastname" => get_post_meta( $id, 'lgm_speaker_lastname', true ),
                "day" => $startDay,
                "time" => $startTime,
                "duration" => $duration,
                "content" => wp_strip_all_tags( get_the_content() ),
                "bio" => get_post_meta( $id, 'lgm_short_bio', true ),
                "additional" => get_post_meta( $id, 'lgm_additional_speakers', true )
            ];
            $slot = getScheduleSlot($startDay, $startTime);
            $schedule[$startDay][$slot][] = $item;
        }
    }
}

// echo("<pre>".print_r($schedule, 1)."</pre>");
// die();

?>
        <style>

        html {
        	background-image: none;
        	background: #12a16d; /* Old browsers */
        	background: -moz-linear-gradient(-45deg,  #12a16d 0%, #e238c7 100%); /* FF3.6-15 */
        	background: -webkit-linear-gradient(-45deg,  #12a16d 0%,#e238c7 100%); /* Chrome10-25,Safari5.1-6 */
        	background: linear-gradient(135deg,  #12a16d 0%,#e238c7 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#12a16d', endColorstr='#e238c7',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
        }
        body {
        	background: none;
        	background-image: none;
        }

        </style>
    	<div id='wrap'>

    				<header class="entry-header">
            <h1 class="entry-title"><?= $pageTitle ?></h1>
            </header>

            <div class="schedule">

                <?php for ($i = 15; $i <= 18; $i++) : ?>
                <div class="day day-<?= $i ?>">
                <h2 class="day-title din">
                <?php
                	if ( $i == 15 ) {
                		echo '<span class="day-span">Friday</span> <span class="month-span">15<sup>th</sup> April</span>';
                	} else if ( $i == 16 ) {
                		echo '<span class="day-span">Saturday</span> <span class="month-span">16<sup>th</sup> April</span>';
                	} else if ( $i == 17 ) {
                		echo '<span class="day-span">Sunday</span> <span class="month-span">17<sup>th</sup> April</span>';
                	} else if ( $i == 18 ) {
                		echo '<span class="day-span">Monday</span> <span class="month-span">18<sup>th</sup> April</span>';
                	}
                 ?>
                 </h2>
	                <?php foreach($schedule[$i] as $j => $slot) : ?>
	                <ul class="slot slot-<?= $time_slot[$i][$j] ?>">
		                <?php foreach($slot as $item) : ?>
		                <?php // TODO: add a popup with details (https://gist.github.com/sniperwolf/5652986) 
		                
		                $toggle = '';
		                
		                if ( ( $item['content'] != '' ) || ( $item['bio'] != '' ) ) {
		                	$toggle = ' toggle item-closed';
		                }
		                
		                ?>

			                <li data-post-id="<?= $item['id'] ?>" class="dur-<?= $item['duration'] ?>">
			                	<div class="item-time"><?= $item['time'] ?></div>
			                	<div class="item-content">
			                		<?php 
			                		
			                		/* Generate h3 title tag:
			                		 * If the item has a presenter, we use the presenter name.			                		 
			                		 * Else, we use the Title field.
			                		*/
			                		
			                		if ( !empty( $item['firstname'] ) ) { ?>
					                		<h3 class="item-presenter din <?= $toggle ?>"><?php
					                			echo $item['firstname'].' ';
					                			echo $item['lastname'];

					                			if ( $item['additional'] != '' ) {
					                				echo ', '.$item['additional'];
					                			}

					                		 ?></h3>
					                		 <p class="item-title <?= $toggle ?>"><?= $item['title'] ?></p>
					                <?php } else {
					                		?>
					                		<h3 class="item-presenter item-title-break din <?= $toggle ?>"><?php
					                			echo $item['title'];
					                		?></h3><?php
					                }

					                	if ( $item['content'] != '' ) {
					                		?>
					                		<div class="item-description js-hidden hidden">
					                			<?= $item['content'] ?>
					                		</div>
					                	<?php
					                	}
					                	
					                	if ( $item['bio'] != '' ) {
					                		?>
					                		<div class="item-description item-bio js-hidden hidden">
					                			<?= $item['bio'] ?>
					                		</div>
					                	<?php
					                	}

					                ?>

			                	</div>

			                </li>

		                <?php endforeach; ?>
	                </ul>
	                <?php endforeach; ?>
                </div>
                <?php endfor; ?>
            </div>
        </div>
		<script type="text/javascript">
			jQuery(document).ready(function($){

							// show description
							$( ".item-content" ).on( "click", ".item-closed", function() {
							  console.log( $( this ).text() );
							  $(this).addClass("item-open");
							  	$(this).siblings(".item-closed").addClass("item-open");
							  $(this).removeClass("item-closed");
							  	$(this).siblings(".item-closed").removeClass("item-closed");
							  $(this).parent().find(".item-description").show();
							});

							// show description
							$( ".item-content" ).on( "click", ".item-open", function() {
							  console.log( $( this ).text() );
							  $(this).addClass("item-closed");
							  	$(this).siblings(".item-open").addClass("item-closed");
							  $(this).removeClass("item-open");
							  	$(this).siblings(".item-open").removeClass("item-open");
							  $(this).parent().find(".item-description").hide();
							});

							// hide description

			});
		</script>

<?php get_footer(); ?>
