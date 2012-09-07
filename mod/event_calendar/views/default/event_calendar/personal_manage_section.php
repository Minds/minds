<?php
$fd = $vars['form_data'];
$event_calendar_personal_manage = elgg_get_plugin_setting('personal_manage', 'event_calendar');
$body = '';

if ($event_calendar_personal_manage == 'by_event') {
	$personal_manage_options = array(
		elgg_echo('event_calendar:personal_manage:by_event:open') => 'open',
		elgg_echo('event_calendar:personal_manage:by_event:closed') => 'closed',
		elgg_echo('event_calendar:personal_manage:by_event:private') => 'private',
	);
	$body .= '<div class="event-calendar-edit-form-block event-calendar-edit-form-membership-block">';
	$body .= '<h2>'.elgg_echo('event_calendar:personal_manage:label').'</h2>';
	$body .= elgg_view("input/radio",array('name' => 'personal_manage','value'=>$fd['personal_manage'],'options'=>$personal_manage_options));
	//$body .= '<p class="event-calendar-description">'.$prefix['personal_manage'].elgg_echo('event_calendar:personal_manage:description').'</p>';
	$body .= '<br clear="both" />';
	$body .= '</div>';
}

echo $body;
