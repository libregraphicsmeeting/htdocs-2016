<?php
/**
 * Template for building the schedule
 */
 
 // Define Variables
 
 $fullcal = get_stylesheet_directory_uri().'/lib/fullcalendar/';
 
 // Define Functions
 
 function lgm_cal_item_markup( $item ) {

 	$cal_item_markup = "<div class='fc-event' data-post-id='".$item['id']."'>";
 	$cal_item_markup .= $item["title"];
 	$cal_item_markup .= "</div>";
 	return $cal_item_markup;

 }
 
 function lgm_cal_item_json( $item ) {
 
 	$cal_item_markup = "post-id  :  '".$item['id']."',";
 	$cal_item_markup .= "title  :  '".$item['title']."',";
 	
 	if ( !empty( $item["start"] ) ) {
 			$cal_item_markup .= "start  :  '".$item["start"]."',";
 		}
 	
 	if ( !empty( $item["end"] ) ) {
 		$cal_item_markup .= "end  :  '".$item["end"]."',";
 	}
 	
 	return $cal_item_markup;
 	
 }
 
 // Generate content
 
 // load events for LGM day 1
     			 
     			 $custom_query = new WP_Query( array(
    					 	'post_type' => 'talk',
    					 	'post_status' => 'any',
    					 	'posts_per_page' => -1,
    					 	'orderby' => 'date',
    					 	'order' => 'ASC',
    					) ); 
    					
    				$talks_scheduled = array();
    				$talks_unscheduled = array();
    				
    			/* Method:
    				
    				1: we load all events
    				2: events that have a start date are not shown
    				3: events are added to appropriate array, depending of day
     				4: markup is generated
     			*/
     			
     			if ($custom_query->have_posts()) : 
     			
     				while( $custom_query->have_posts() ) : $custom_query->the_post();
     					
     					// Build the talk object
     					
     					$talk_object = array();
     					
     					$id = get_the_ID();
     					$talk_object["id"] = $id;
     					$talk_object["title"] = get_the_title();
     					
     					$talk_object["start"] = get_post_meta( $id, '_mem_start_date', true );
     					$talk_object["end"] = get_post_meta( $id, '_mem_end_date', true );
     					
     					// Do we have a start date?
     					
     					if ( empty( $talk_object["start"] ) ) {
 
     						// What is the prefered day?
     						
     						$preferred_day = get_post_meta( $id, 'lgm_preferred_day', true );
     						// echo $preferred_day;
     						
     						if ( 'Thursday' == $preferred_day ) {
     						
     							$talks_unscheduled['Thursday'][] = $talk_object;
     						
     						} else if ( 'Friday' == $preferred_day ) {
     						
     							$talks_unscheduled['Friday'][] = $talk_object;
     						
     						} else if ( 'Saturday' == $preferred_day ) {
     						
     							$talks_unscheduled['Saturday'][] = $talk_object;
     						
     						} else if ( 'Sunday' == $preferred_day ) {
     						
     							$talks_unscheduled['Sunday'][] = $talk_object;
     						
     						} else {
     						
     							$talks_unscheduled['Other'][] = $talk_object;
     						
     						}
     					
     					} else {
     					
     						// we HAVE a start date - add object to array of scheduled talks.
     						
     						$talks_scheduled[] = $talk_object;
     						
     					}
     					
     				endwhile;
     				 
     			endif;
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
	
									// EVENTS
									// query for talks that have a start event
									<?php 
									
										if ( !empty($talks_scheduled) ) {
										
											echo 'events: [';
										
												foreach ($talks_scheduled as $key => $item) {
														?> {  
														<?php 
														echo lgm_cal_item_json( $item );
														?> 
														}, 
														<?php 
												}
												
											echo '],';		
										}
										
									 ?>
									
				    			// end LGM custom settings
				    			
				    			drop: function() {
				    					$(this).remove();
				    			},
				    			
				    			eventDrop: function(event, delta) {
				    					// http://fullcalendar.io/docs/event_ui/eventDrop/
					            // alert(event.title + ' was moved ' + delta + ' days\n' + '(should probably update your database)');
					        },
					        eventReceive: function(event, delta) {
					        		// http://fullcalendar.io/docs/event_ui/eventDrop/
					            // alert(event.title + ' was placed ' + delta + ' days\n' + '(should probably update your database)');
					        }
				    		});
				    
				    
				    	});
				
				
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
    		width: 1100px;
    		margin: 0 auto;
    	}
    		
    	#external-events {
    		float: left;
    		width: 150px;
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
    		width: 900px;
    	}

    
    </style>
    <body>
    	<div id='wrap'>
    
    		<div id='external-events'>
    			<h4>Draggable Events</h4>
    			<?php 
    			 
    			// generate the markup...
    			
    			if ( !empty($talks_unscheduled["Thursday"]) ) {
    			  echo '<h5>Thursday</h5>';
    					foreach ($talks_unscheduled["Thursday"] as $key => $item) {
    							echo lgm_cal_item_markup( $item );
    					}
    			}
    			
    			if ( !empty($talks_unscheduled["Friday"]) ) {
    			  echo '<h5>Friday</h5>';
    					foreach ($talks_unscheduled["Friday"] as $key => $item) {
    							echo lgm_cal_item_markup( $item );
    					}
    			}
    			
    			if ( !empty($talks_unscheduled["Saturday"]) ) {
    			  echo '<h5>Saturday</h5>';
    					foreach ($talks_unscheduled["Saturday"] as $key => $item) {
    							echo lgm_cal_item_markup( $item );
    					}
    			}
    			
    			if ( !empty($talks_unscheduled["Sunday"]) ) {
    			  echo '<h5>Sunday</h5>';
    					foreach ($talks_unscheduled["Sunday"] as $key => $item) {
    							echo lgm_cal_item_markup( $item );
    					}
    			}
    			
    			if ( !empty($talks_unscheduled["Other"]) ) {
    			  echo '<h5>OTHERS</h5>';
    					foreach ($talks_unscheduled["Other"] as $key => $item) {
    							echo lgm_cal_item_markup( $item );
    					}
    			}

    			 ?>
    			
    		</div>
    
    		<div id='calendar'></div>
    
    		<div style='clear:both'></div>
    
    	</div>
   </body>
</html>