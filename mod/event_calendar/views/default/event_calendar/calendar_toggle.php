<?php
$event = $vars['event_calendar_event'];
$user = $vars['entity'];
$container = get_entity($event->container_guid);
if (event_calendar_has_personal_event($event->guid, $user->guid)) {
	$label = elgg_echo('event_calendar:remove_from_the_calendar_button');
} else {
	$label = elgg_echo('event_calendar:add_to_the_calendar');
}

if ($container->canEdit()) {	
	$button = elgg_view('input/button',array(
		'id'=>'event_calendar_user_data_'.$event->guid.'_'.$user->guid,
		'class' => "event-calendar-personal-calendar-toggle",
		'value' => $label,
	));
	echo '<div class="event-calendar-personal-calendar-toggle-wrapper">'.$button.'<div>';
}
