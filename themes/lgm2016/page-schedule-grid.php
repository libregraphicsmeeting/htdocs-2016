<?php
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
    ],
    17 => [
        11*60+00,
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

$custom_query = new WP_Query(array(
    'post_type' => 'talk',
    'post_status' => 'any',
    'posts_per_page' => -1,
    // 'orderby' => 'date',
    'orderby' => 'meta_value',
    'meta_key' => '_mem_start_date',
    'order' => 'ASC',
));

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

echo("<pre>".print_r($time_slot, 1)."</pre>");
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
            ];
            $slot = getScheduleSlot($startDay, $startTime);
            $schedule[$startDay][$slot][] = $item;
        }
    }
}

// echo("<pre>".print_r($schedule, 1)."</pre>");
// die();

?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <style>
    </style>
    <body>
        <style>
        .schedule {
            display:table-row;
            vertical-align:top;
            background-color:lightgray;
        }
        .schedule .time {
            width:80px;
            display:table-cell;
            vertical-align:top;
        }
        .schedule .day {
            width:300px;
            display:table-cell;
            vertical-align:top;
        }
        .schedule .time ul li{
            height:70px;
        }
        .schedule .day-15 {
            background-color:orange;
        }
        .schedule .day-15 .slot-660 {
            margin-top:80px;
            background-color:lightblue;
        }
        .schedule .day-16 {
            background-color:green;
        }
        </style>
    	<div id='wrap'>
            <h1>blah</h1>
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
                <ul class="slot-<?= $time_slot[$i][$j] ?>">
                <?php foreach($slot as $item) : ?>
                <li><?= sprintf('%s (%s): %s', $item['time'], $item['duration'], $item['title']) ?></li>
                <?php endforeach; ?>
                </ul>
                <?php endforeach; ?>
                </div>
                <?php endfor; ?>
            </div>
        </div>
   </body>
</html>
