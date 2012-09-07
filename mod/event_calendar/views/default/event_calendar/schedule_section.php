<?php
$event = $vars['event'];
$fd = $vars['form_data'];
$event_calendar_repeated_events = elgg_get_plugin_setting('repeated_events', 'event_calendar');

$body = '<div class="event-calendar-edit-date-wrapper">';
$body .= elgg_view('event_calendar/datetime_edit', 
	array(
		'start_date' => $fd['start_date'],
		'end_date' => $fd['end_date'],
		'start_time' => $fd['start_time'],
		'end_time' => $fd['end_time'],
		'prefix' => $vars['prefix'],
));
if ($event_calendar_repeated_events == 'yes') {
	$body .= elgg_view('event_calendar/repeat_form_element',$vars);
}

$body .= elgg_view('event_calendar/reminder_section',$vars);
$body .= '</div>';

echo $body;
