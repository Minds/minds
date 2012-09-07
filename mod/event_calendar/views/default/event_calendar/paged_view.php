<?php
elgg_load_js('elgg.event_calendar');
$nav = elgg_view('navigation/pagination',array(
			
//												'baseurl' => $_SERVER['REQUEST_URI'],
												'baseurl' => $_SERVER['SCRIPT_NAME'].'/?'.$_SERVER['QUERY_STRING'],
												'offset' => $vars['offset'],
												'count' => $vars['count'],
												'limit' => $vars['limit'],
			
														));
$event_calendar_times = elgg_get_plugin_setting('times', 'event_calendar');
$event_calendar_personal_manage = elgg_get_plugin_setting('personal_manage', 'event_calendar');
$events = $vars['events'];
$html = '';
$date_format = 'F Y';
$current_month = '';
if ($events) {
	foreach($events as $event) {
		$month = date($date_format,$event->start_date);
		if ($month != $current_month) {
			if ($html) {
				$html .= elgg_view('event_calendar/paged_footer');
			}
			$html .= elgg_view('event_calendar/paged_header',array('date'=>$month,'personal_manage'=>$event_calendar_personal_manage));
			
			$current_month = $month;
		}
		$html .= elgg_view('event_calendar/paged_item_view',array('event'=>$event,'times'=>$event_calendar_times,'personal_manage'=>$event_calendar_personal_manage));
	}
	$html .= elgg_view('event_calendar/paged_footer');
}
$msgs = '<div id="event_calendar_paged_messages"></div>';
$html = $msgs.$nav.'<div class="event_calendar_paged">'.$html.'</div>'.$nav;

echo $html;
