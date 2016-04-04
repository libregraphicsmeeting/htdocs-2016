<?php

$pageTitle = get_the_title();

/**
 * Template for building the schedule
 */

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

include(get_stylesheet_directory().'/page-schedule-class.php');
$pageSchedule = new LGMPageSchedule();

while ($item = $pageSchedule->next()) {
    if ($item['time']) {
        $slot = getScheduleSlot($item['day'], $item['time']);
        $schedule[$item['day']][$slot][] = $item;
    }
}
// echo("<pre>".print_r($schedule, 1)."</pre>");
// die();

?>
<?php include(get_stylesheet_directory().'/header.php') ?>
        <style>
        .schedule {
            display:table-row;
            vertical-align:top;
            background-color:lightgray;
        }
        .schedule .day {
            width:300px;
            display:table-cell;
            vertical-align:top;
        }
        .schedule .time {
            background-color:yellow;
            width:80px;
        }
        .schedule .time ul {
            list-style-type: none;
            padding:0;
            margin:0;
        }
        <?php function cssSH($h, $m = 0) { return 80*$h + (80/60*$m);} ?>
        .schedule .time ul li{
            height:<?= cssSH(1) ?>px;
        }
        .schedule .slot {
            /* overflow-y: hidden; */
            overflow-y: scroll;
            background-color:lightblue;
        }
        .schedule ul.slot {
            list-style-type: none;
            padding:0;
            margin:0;
        }

        .schedule .day-15 .slot-660 {
            margin-top:<?= cssSH(1) ?>px; /* 1'00 */
            height: <?= cssSH(1,40) ?>px; /* 1'40 */
        }
        .schedule .day-15 .slot-800 {
            margin-top:<?= cssSH(0,40) ?>px; /* 0'40 */
            height: <?= cssSH(2,20) ?>px; /* 2'20 */
        }
        .schedule .day-15 .slot-980 {
            margin-top:<?= cssSH(1,20) ?>px; /* 1'20 */
            height: <?= cssSH(2) ?>px; /* 2'00 */
        }

        .schedule .day-16 .slot-600 {
            height: <?= cssSH(2,20) ?>px; /* 2'20 */
        }
        .schedule .day-16 .slot-780 {
            margin-top:<?= cssSH(0,40) ?>px; /* 0'40 */
            height: <?= cssSH(2) ?>px; /* 2'00 */
        }
        .schedule .day-16 .slot-940 {
            margin-top:<?= cssSH(0,40) ?>px; /* 0'40 */
            height: <?= cssSH(1) ?>px; /* 1'00 */
        }
        .schedule .day-16 .slot-1000 {
            height: <?= cssSH(1) ?>px;
        }
        .schedule .day-16 .slot-1080 {
            margin-top:<?= cssSH(0,20) ?>px;
            height: <?= cssSH(1) ?>px;
        }
        .schedule .day-16 .slot-1140 {
            height: <?= cssSH(1) ?>px;
        }
        .schedule .day-17 .slot-600 {
            height: <?= cssSH(2,20) ?>px; /* 2'20 */
        }
        .schedule .day-17 .slot-780 {
            margin-top:<?= cssSH(0,40) ?>px; /* 0'40 */
            height: <?= cssSH(2) ?>px; /* 2'00 */
        }
        .schedule .day-17 .slot-940 {
            margin-top:<?= cssSH(0,40) ?>px;
            height: <?= cssSH(2) ?>px; /* 2'00 */
        }
        .schedule .day-17 .slot-1080 {
            margin-top:<?= cssSH(0,20) ?>px; /* 0'20 */
            height: <?= cssSH(0,40) ?>px; /* 0'40 */
        }
        .schedule .day-18 .slot-600 {
            height: <?= cssSH(2) ?>px; /* 2'00 */
        }
        /*
        .schedule .day-18 .slot-600 {
            -webkit-transform: rotate(-90deg);
            -moz-transform: rotate(-90deg);
            -ms-transform: rotate(-90deg);
            -o-transform: rotate(-90deg);
            transform: rotate(-90deg);
        }
        */
        .schedule .day-18 .slot-780 {
            margin-top:<?= cssSH(1) ?>px; /* 1'00 */
            height: <?= cssSH(1) ?>px; /* 1'00 */
        }
        .schedule .day-18 .slot-920 {
            margin-top:<?= cssSH(1,20) ?>px; /* 1'20 */
            height: <?= cssSH(1) ?>px; /* 1'00 */
        }
        </style>
    	<div id='wrap'>
            <h1><?= $pageTitle ?></h1>
            <div class="schedule">
                <div class="day time">
                    <ul>
                    <?php foreach ($time as $hour) : ?>
                        <li><?= $hour ?></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
                <?php for ($i = 15; $i <= 18; $i++) : ?>
                <div class="day day-<?= $i ?>">
                <?php foreach($schedule[$i] as $j => $slot) : ?>
                <ul class="slot slot-<?= $time_slot[$i][$j] ?>">
                <?php foreach($slot as $item) : ?>
                <?php // TODO: add a popup with details (https://gist.github.com/sniperwolf/5652986) ?>
                <li data-post-id="<?= $item['id'] ?>"><?= sprintf('%s (%s): %s', $item['time'], $item['duration'], $item['title']) ?></li>
                <?php endforeach; ?>
                </ul>
                <?php endforeach; ?>
                </div>
                <?php endfor; ?>
            </div>
        </div>
		<div id="popup" style="display:none;height:300px;width:500px;position:absolute; border:3px solid green; background-color:white;"></div>
		<script>
		(function($) {
			$(document).ready(function() {
				$('ul.slot li').bind('click', function (event) {
                    console.log(event);

                    var id = jQuery(this).attr('data-post-id');
                    $( "#popup" ).load(
                        "<?php echo admin_url('admin-ajax.php'); ?>",
                        {
                            action: 'lgm_get_talk_detail',
                            "post-id": id
                        },
                        function() {
                            // alert( "Load "+id+" was performed." );
                        }
                    );

                    // TODO: add a transparent filling div below popup for closing on click
                    // TODO: only place to left,top if the full div fits on screen
					$('#popup').css('left',$(this).position().left);
					$('#popup').css('top',$(this).position().top);
					$('#popup').css('display','inline');     
					$("#popup").css("position", "absolute");  // <<< also make it absolute!
				});
			});
		}(jQuery));
		</script>
<?php include(get_stylesheet_directory().'/footer.php') ?>
