<?php

/**
 * Elgg event_calendar group profile content
 *
 * @package event_calendar
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2008
 * @link http://radagast.biz/
 *
 */

elgg_load_library('elgg:event_calendar');

$page_owner_entity = elgg_get_page_owner_entity();

if (event_calendar_activated_for_group($page_owner_entity)) {
    $num = 5;
    // Get the upcoming events
    $start_date = time(); // now
    $end_date = $start_date + 60*60*24*365*2; // maximum is two years from now
	$events = event_calendar_get_events_between($start_date,$end_date,false,$num,0,elgg_get_page_owner_guid());
		
	// If there are any events to view, view them
	if (is_array($events) && sizeof($events) > 0) {

		foreach($events as $event) {
			echo elgg_view("object/event_calendar",array('entity' => $event['event']));
		}
			
    }
}
