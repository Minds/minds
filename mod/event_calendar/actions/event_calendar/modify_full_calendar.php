<?php
elgg_load_library('elgg:event_calendar');
$event_guid = get_input('event_guid',0);
$day_delta = get_input('dayDelta');
$minute_delta = get_input('minuteDelta','');
$start_time = get_input('startTime','');
$resend = get_input('resend','');
$minutes = get_input('minutes');
$iso_date = get_input('iso_date');
$result = event_calendar_modify_full_calendar($event_guid,$day_delta,$minute_delta,$start_time,$resend,$minutes,$iso_date);
if ($result) {
	$response = array('success'=>TRUE);
	// special handling for event polls
	if (is_array($result)) {
		$response['minutes'] = $result['minutes'];
		$response['iso_date'] = $result['iso_date'];
	}
} else {	
	$response = array('success'=>FALSE, 'message' =>elgg_echo('event_calendar:modify_full_calendar:error'));
}

echo json_encode($response);

exit;
