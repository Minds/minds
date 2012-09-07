<?php
$fd = $vars['form_data'];
$event_calendar_hide_access = elgg_get_plugin_setting('hide_access', 'event_calendar');
$body = '<div class="event-calendar-edit-form-block event-calendar-edit-form-share-block">';
$body .= '<h2>'.elgg_echo('event_calendar:permissions:header').'</h2>';
if($event_calendar_hide_access == 'yes') {
	$event_calendar_default_access = elgg_get_plugin_setting('default_access', 'event_calendar');
	if($event_calendar_default_access) {
		$body .= elgg_view("input/hidden",array('name' => 'access_id','value'=>$event_calendar_default_access));
	} else {
		$body .= elgg_view("input/hidden",array('name' => 'access_id','value'=>ACCESS_DEFAULT));
	}
} else {
	$body .= '<p><label>'.elgg_echo('event_calendar:read_access').'</label>';
	$body .= elgg_view("input/access",array('name' => 'access_id','value'=>$fd['access_id']));
	$body .= '</p>';
}
if (elgg_plugin_exists('entity_admins')) {
	$body .= elgg_echo('event_calendar:share_ownership:label');
	$body .= '<br />';
	$body .= elgg_echo('event_calendar:share_ownership:description');
	$body .= elgg_view('input/entity_admins_dropdown',array('entity'=>$vars['event']));
}
$body .= '</div>';

echo $body;
