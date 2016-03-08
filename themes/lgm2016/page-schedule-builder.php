<?php
/**
 * Template for building the schedule
 */

$fullcal = get_stylesheet_directory_uri().'/lib/fullcalendar/';
 
function lgm_cal_item_markup( $item ) {

	$cal_item_markup = "<div class='fc-event' data-post-id='".$item['id']."'>";
	$cal_item_markup .= $item["title"];
	$cal_item_markup .= "</div>";
	return $cal_item_markup;

}

function lgm_cal_events_json($list) {
    $result = [];
    foreach ($list as $item) {
        $iitem = [
            'post-id' =>  $item['id'],
            'title' =>  $item['title'],
        ];
        if ( !empty( $item['start'] ) ) {
            $iitem['start'] = $item['start'];
        }
        if ( !empty( $item['end'] ) ) {
            $iitem['end'] = $item['end'];
        }
        $result[] = $iitem;
    }
    return json_encode($result);
}

// Generate content

$custom_query = new WP_Query(array(
    'post_type' => 'talk',
    'post_status' => 'any',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'ASC',
));

$talks_scheduled = array();
$talks_unscheduled = array(
    'Thursday' => [],
    'Friday' => [],
    'Saturday' => [],
    'Sunday' => [],
    'Other' => [],
);

/**
    1: we load all events
    2: events that have a start date are not shown
    3: events are added to appropriate array, depending of day
    4: markup is generated
 */
     			
if ($custom_query->have_posts()) {

    while( $custom_query->have_posts() ) {
        $custom_query->the_post();

        // Build the talk object

        $talk_object = array();

        $id = get_the_ID();
        $title = get_the_title();
        $title .= ' ('.get_post_meta( $id, 'lgm_speaker_firstname', true ).' ';
        $title .= get_post_meta( $id, 'lgm_speaker_lastname', true ).')';

        $talk_object = [
            "id" => $id,
            "title" => $title,
            "start" => get_post_meta( $id, '_mem_start_date', true),
            "end" => get_post_meta( $id, '_mem_end_date', true),
        ];

        /**
         * add talk_object without a date to talks_unscheduled and the other to talks_scheduled
         */
        if ( empty( $talk_object["start"] ) ) {
            $preferred_day = get_post_meta( $id, 'lgm_preferred_day', true );
            // echo $preferred_day;
            if (array_key_exists($preferred_day, $talks_unscheduled)) {
                $talks_unscheduled[$preferred_day][] = $talk_object;
            } else {
                $talks_unscheduled['Other'][] = $talk_object;
            }

        } else {
            $talks_scheduled[] = $talk_object;
        }

    }
}
wp_reset_postdata();

?>
<!doctype html>
<html class="no-js" lang="" moznomarginboxes mozdisallowselectionprint>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>LGM Schedule Builder</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="robots" content="noindex, nofollow">
            
            <link href='<?php echo $fullcal ?>/fullcalendar.css' rel='stylesheet' />
            <link href='<?php echo $fullcal ?>/fullcalendar.print.css' rel='stylesheet' media='print' />
            <script src='<?php echo $fullcal ?>/lib/moment.min.js'></script>
            <script src='<?php echo $fullcal ?>/lib/jquery.min.js'></script>
            <script src='<?php echo $fullcal ?>/lib/jquery-ui.custom.min.js'></script>
            <script src='<?php echo $fullcal ?>/fullcalendar.min.js'></script>
            
            <!-- and our custom script -->
            <script>
				$(document).ready(function() {
				    
				    
                /* initialize the external events
                -----------------------------------------------------------------*/
        
                $('#external-events .fc-event').each(function() {
        
                    // store data so the calendar knows to render an event upon drop
                    $(this).data('event', {
                        title: $.trim($(this).text()), // use the element's text as the event title
                        stick: true // maintain when user navigates (see docs on the renderEvent method)
                    });
        
                    // make the event draggable using jQuery UI
                    $(this).draggable({
                        zIndex: 999,
                        revert: true,      // will cause the event to go back to its
                        revertDuration: 0  //  original position after the drag
                    });
        
                });
        
        
                /* initialize the calendar
                -----------------------------------------------------------------*/
        
                $('#calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'agendaDay'
                    },
                    editable: true,
                    droppable: true, // this allows things to be dropped onto the calendar
                    
                    // LGM Custom Settings:

                    axisFormat: 'HH:mm',
                    scrollTime: '08:00:00',
                    defaultView: 'agendaDay',
                    slotDuration: '00:10:00',
                    defaultTimedEventDuration: '00:20:00',
                    defaultDate: '2016-04-15',
                    events: <?= lgm_cal_events_json($talks_scheduled) ?>,

                    // event creation
                    drop: function(date, jsEvent, ui, resourceId) {
                        $(this).remove();
                        // console.log('data-id', jsEvent.toElement.attributes['data-post-id'].nodeValue);
                        post_id = jsEvent.toElement.attributes['data-post-id'].nodeValue;
                        ale_EventCreate(date, post_id);
                    },
                    // event moving
                    eventDrop: function(event) {
                            // console.log('event', event);
                            ale_EventDrag(event);
                    },
                    // event resizing
                    eventResize: function(event) {
                            // console.log('event', event);
                            ale_EventResize(event);
                    },
                    eventClick: function(calEvent, jsEvent, view) {
                        console.log('calEvent', calEvent);
                        var url = '<?= admin_url() ?>post.php?post='+calEvent['post-id']+'&action=edit'
                        var win = window.open(url, '_blank');
                    }
                });
            });

            /**
             * reacting to changes in the calendar and storing in wordpress through ajax
             */
            var ale_EventStorage = {};
            var ale_EventStorageDirty = false;

            $(document).ready(function() {
               $('form[name="scheduleBuilderSave"]').click(ale_EventSave);
            });

            function ale_EventSave() {
               // console.log('ale_EventStorageDirty', ale_EventStorageDirty);
               if (ale_EventStorageDirty) {
                   // console.log('ale_EventStorage', ale_EventStorage);
                   $.ajax({
                        type: 'POST',
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {
                            action: 'save_schedule_builder_event',
                            // data: dataPost 
                            data: ale_EventStorage
                        },
                        success: function (response) {
                            console.log('response', response);
                            if (response.success) {
                                // console.log('emptying of the storage list disabled');
                                ale_EventStorage = {};
                                ale_EventStorageDirty = false;
                                // console.log('response success', response);
                            } else {
                                console.log('response error', response);
                            }
                        }
                    });
               }
               return false;
            }

            function ale_EventCreate(date, post_id) {
                // console.log('post_id', post_id);
                // console.log('date', date.toISOString());
                var start = date.toISOString();
                ale_EventStorage[post_id] = {
                    'post-id': post_id,
                    'start': start,
                };
                ale_EventStorageDirty = true;
            }

            function ale_EventDrag(event) {
                // console.log('event', event);
                var post_id = event['post-id'];
                // console.log('post_id', post_id);
                var start = event.start.toISOString();
                // console.log('start', start);
                if (post_id in ale_EventStorage) {
                    ale_EventStorage[post_id].start = start;
                } else {
                    ale_EventStorage[post_id] = {
                        'post-id': post_id,
                        'start': start
                    };
                }
                ale_EventStorageDirty = true;
            }

            function ale_EventResize(event) {
                // console.log('event', event);
                var post_id = event['post-id'];
                var duration = (event.end - event.start)/1000/60;
                // console.log('duration', duration);
                if (post_id in ale_EventStorage) {
                    ale_EventStorage[post_id].duration = duration;
                } else {
                    ale_EventStorage[post_id] = {
                        'post-id': post_id,
                        'duration': duration 
                    };
                }
                ale_EventStorageDirty = true;
            }
    
    
    </script>
				
    </head>
    <style>
    
    	body {
    		margin-top: 40px;
    		text-align: center;
    		font-size: 14px;
    		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    	}
    		
    	#wrap {
    		width: 100%;
    		margin: 0 auto;
    	}
    		
    	#external-events {
    		float: left;
    		width: 25%;
    		padding: 0 10px;
    		border: 1px solid #ccc;
    		background: #eee;
    		text-align: left;
    	}
    		
    	#external-events h4 {
    		font-size: 16px;
    		margin-top: 0;
    		padding-top: 1em;
    	}
    		
    	#external-events .fc-event {
    		margin: 10px 0;
    		cursor: pointer;
    	}
    		
    	#external-events p {
    		margin: 1.5em 0;
    		font-size: 11px;
    		color: #666;
    	}
    		
    	#external-events p input {
    		margin: 0;
    		vertical-align: middle;
    	}
    
    	#calendar {
    		float: right;
    		width: 70%;
    		position: fixed;
    		top:  0px;
    		right: 10px;
    		margin: 0 0 0 0;
    		-moz-box-sizing: border-box;
    		box-sizing: border-box;
    	}
    	
    	.save-button {
    		margin-top: 1em;
    	}

    
    </style>
    <body>
    	<div id='wrap'>
    
    		<div id='external-events'>
                <form name = "scheduleBuilderSave"><button name="save" class="save-button">save</button></form>
    			<h4>Draggable Events</h4>
    			<?php 
    			// generate the markup...
                foreach ($talks_unscheduled as $key => $value) {
                    if ( !empty($value) ) {
                        echo '<h5>'.$key.'</h5>';
    					foreach ($value as $item) {
                            echo lgm_cal_item_markup( $item );
    					}
                    }

                }
    			 
            ?>
    			
    		</div>
    
    		<div id='calendar'></div>
    
    		<div style='clear:both'></div>
    
    	</div>
   </body>
</html>
