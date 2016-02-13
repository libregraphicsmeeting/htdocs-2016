<?php
/**
 * Template for building the schedule
 */
 // get_stylesheet_directory_uri()
 
 $fullcal = get_stylesheet_directory_uri().'/lib/fullcalendar/';
 
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
				<script src='<?php echo get_stylesheet_directory_uri() ?>/js/schedule-builder.js'></script>
				
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
    			<div class='fc-event'>My Event 1</div>
    			<div class='fc-event'>My Event 2</div>
    			<div class='fc-event'>My Event 3</div>
    			<div class='fc-event'>My Event 4</div>
    			<div class='fc-event'>My Event 5</div>
    			<?php 
    			
    			 // load events for LGM day 1
    			 
    			 $custom_query = new WP_Query( array(
   					 	'post_type' => 'talk',
   					 	'post_status' => 'any',
   					 	'posts_per_page' => -1,
   					 	'orderby' => 'date',
   					 	'order' => 'ASC',
   					) ); 
   					
   				$list_of_talks = array();
   				
   					 
   				/* Method:
   				1: we load all events
   				2: events that have a start date are not shown
   				3: events are added to appropriate array, depending of day
    			4: markup is generated
    			*/
    			
    			if ($custom_query->have_posts()) : 
    			
    				while( $custom_query->have_posts() ) : $custom_query->the_post();
    					
    					// do we have a start date?
    					
    					
    					
    				endwhile;
    				 
    			endif;
    			wp_reset_postdata();
    			
    			 ?>
    			
    		</div>
    
    		<div id='calendar'></div>
    
    		<div style='clear:both'></div>
    
    	</div>
   </body>
</html>