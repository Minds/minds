<?php
$event_calendar_times = elgg_get_plugin_setting('times', 'event_calendar');
$event_calendar_hide_end = elgg_get_plugin_setting('hide_end', 'event_calendar');
$prefix = $vars['prefix'];
$body = '';

if ($event_calendar_times != 'no') {
	if ($event_calendar_hide_end != 'yes') {
		$body .= '<p><label>'.elgg_echo('event_calendar:from_label').'</label>';
	}
	$body .= elgg_view("event_calendar/input/date_local",array(
		//'timestamp'=>TRUE, 
		'autocomplete'=>'off',
		'class'=>'event-calendar-compressed-date',
		'name' => 'start_date',
		'value'=>$vars['start_date']));
	$body .= '<span id="event-calendar-start-time-wrapper">';
	$body .= elgg_view("input/timepicker",array('name' => 'start_time','value'=>$vars['start_time']));
	$body .= '</span>';
	if ($event_calendar_hide_end != 'yes') {
		$body .= '</p><p id="event-calendar-to-time-wrapper"><label>'.elgg_echo('event_calendar:to_label').'</label>';
		$body .= elgg_view("event_calendar/input/date_local",array(
			//'timestamp'=>TRUE,
			'autocomplete'=>'off',
			'class'=>'event-calendar-compressed-date',
			'name' => 'end_date',
			'value'=>$vars['end_date'],
		));
		$body .= '<span id="event-calendar-end-time-wrapper">';
		$body .= elgg_view("input/timepicker",array('name' => 'end_time','value'=>$vars['end_time']));
		$body .= '</span>';
	}
	$body .= '</p></span>';
} else {

	$body .= '<p><label>'.elgg_echo("event_calendar:start_date_label").'<br />';
	$body .= elgg_view("event_calendar/input/date_local",array('timestamp'=>TRUE, 'autocomplete'=>'off','name' => 'start_date','value'=>$vars['start_date']));
	$body .= '</label></p>';
	$body .= '<p class="description">'.$prefix['start_date'].elgg_echo('event_calendar:start_date_description').'</p>';
	
	if ($event_calendar_hide_end != 'yes') {		
		$body .= '<p id="event-calendar-to-time-wrapper"><label>'.elgg_echo("event_calendar:end_date_label").'<br />';
		$body .= elgg_view("event_calendar/input/date_local",array('timestamp'=>TRUE,'autocomplete'=>'off','name' => 'end_date','value'=>$vars['end_date']));
		$body .= '</label></p>';
		//$body .= '<p class="description">'.$prefix['end_date'].elgg_echo('event_calendar:end_date_description').'</p>';
	}
}

echo $body;