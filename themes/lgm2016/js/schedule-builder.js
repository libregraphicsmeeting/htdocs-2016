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
//    			timeFormat: 'HH:mm',
    			axisFormat: 'HH:mm',
    			scrollTime: '08:00:00',
    			defaultView: 'agendaDay',
    			slotDuration: '00:10:00',
    			defaultTimedEventDuration: '00:20:00',
    			defaultDate: '2016-04-15',
    			
//    			setDefaults({
//	    			axisFormat: 'HH:mm',
//	    			timeFormat: {
//	    			    agenda: 'H:mm{ - h:mm}'
//	    			},
//    			}),
    			// end LGM custom settings
    			
    			drop: function() {
    					$(this).remove();
    			},
    			
    			eventDrop: function(event, delta) {
    					// http://fullcalendar.io/docs/event_ui/eventDrop/
	            alert(event.title + ' was moved ' + delta + ' days\n' +
	                '(should probably update your database)');
	        },
	        eventReceive: function(event, delta) {
	        		// http://fullcalendar.io/docs/event_ui/eventDrop/
	            alert(event.title + ' was placed ' + delta + ' days\n' +
	                '(should probably update your database)');
	        }
    		});
    
    
    	});
