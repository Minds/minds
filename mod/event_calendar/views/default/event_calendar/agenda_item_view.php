<?php
$event = $vars['event'];
$times = $vars['times'];
$time_bit = '';
if ($times != 'no') {
	if (is_numeric($event->start_time)) {
		$time_bit = event_calendar_convert_time($event->start_time);
	}
	if (is_numeric($event->end_time)) {
		$time_bit .= " - ".event_calendar_convert_time($event->end_time);
	}
	$time_bit .= ' ';
}
$info = '<tr>';
$info .= '<td class="event_calendar_agenda_time">'.$time_bit.'</td>';
$info .= '<td class="event_calendar_agenda_title"><a href="'.$event->getUrl().'">'.$event->title.'</a></td>';
$info .= '<td class="event_calendar_agenda_venue">'.$event->venue.'</td>';
$info .= '</tr>';
if (trim($event->description)) {
	$info .= '<tr class="event_calendar_agenda_description">';
	$info .= '<td class="event_calendar_agenda_time">&nbsp;</td>';
	$info .= '<td colspan="2">'.$event->description.'</td></tr>';
}

echo $info;
?>