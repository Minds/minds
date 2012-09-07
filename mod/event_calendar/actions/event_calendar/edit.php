<?php

/**
 * Edit action
 * 
 * @package event_calendar
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2008
 * @link http://radagast.biz/
 * 
 */

elgg_load_library('elgg:event_calendar');

// start a new sticky form session in case of failure
elgg_make_sticky_form('event_calendar');
      
$event_guid = get_input('event_guid',0);
$group_guid = get_input('group_guid',0);
$event = event_calendar_set_event_from_form($event_guid,$group_guid);
if ($event) {
	// remove sticky form entries
	elgg_clear_sticky_form('event_calendar');
	$user_guid = elgg_get_logged_in_user_guid();
	if ($event_guid) {
		add_to_river('river/object/event_calendar/update','update',$user_guid,$event_guid);
		system_message(elgg_echo('event_calendar:manage_event_response'));
	} else {
		$event_calendar_autopersonal = elgg_get_plugin_setting('autopersonal', 'event_calendar');
		if (!$event_calendar_autopersonal || ($event_calendar_autopersonal == 'yes')) {
			event_calendar_add_personal_event($event->guid,$user_guid);
		}
		add_to_river('river/object/event_calendar/create','create',$user_guid,$event->guid);
		system_message(elgg_echo('event_calendar:add_event_response'));
	}
	
	if ($event->schedule_type == 'poll') {
		forward('event_poll/add/'.$event->guid);
	}
	 
	forward($event->getURL());
} else {
	// redisplay form with error message
	register_error(elgg_echo('event_calendar:manage_event_error'));
	if ($event_guid) {
		forward('event_calendar/edit/'.$event_guid);
	} else {
		if ($group_guid) {
			forward('event_calendar/add/'.$group_guid);
		} else {
			forward('event_calendar/add/');
		}
	}
}
