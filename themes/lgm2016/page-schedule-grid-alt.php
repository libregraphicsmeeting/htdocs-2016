<?php
/**
 * Template for building the schedule
 */

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
        
               <?php function cssSH($h, $m = 0) { return 80*$h + (80/60*$m);} ?>
        

        </style>
    	<div id='wrap'>
            <h1>The schedule draft</h1>
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
		                <?php // TODO: add a popup with details (https://gist.github.com/sniperwolf/5652986) ?>
		                
			                <li data-post-id="<?= $item['id'] ?>" class="dur-<?= $item['duration'] ?>">
			                	<div class="item-time"><?= $item['time'] ?></div>
			                	<div class="item-content">
			                		<?php if ( !empty( $item['firstname'] ) ) { ?>
					                		<h3 class="item-presenter din"><?php 
					                			echo $item['firstname'].' '; 
					                			echo $item['lastname'];
					                			
					                			if ( $item['additional'] != '' ) {
					                				echo ', '.$item['additional'];
					                			}
					                		 
					                		 ?></h3>
					                <?php } ?>
			                		 <p class="item-title"><?= $item['title'] ?></p>
			                	</div>
			                	
			                </li>
		                
		                <?php endforeach; ?>
	                </ul>
	                <?php endforeach; ?>
                </div>
                <?php endfor; ?>
            </div>
        </div>
		<div id="popup" style="display:none;height:300px;width:500px;position:absolute; border:3px solid green;">Hi</div>
		
		
<?php get_footer(); ?>
