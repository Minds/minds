<?php
$event = $vars['event'];
$users = event_calendar_get_users_for_event($event->guid,0);
$guids = array();
foreach($users as $user) {
	$guids[] = $user->guid;
}
// TODO: if the event container is a group need to restrict user picker to the members of the group?
$content = elgg_view('input/userpicker_plus',array('value'=> $guids));
$content .= '<br /><br />';
$content .= elgg_view('input/hidden',array('name'=>'event_guid','value'=>$event->guid));
$content .= elgg_view('input/submit',array('value'=>elgg_echo('submit'),'name'=>'submit_manage_subscribers','id'=>'submit-manage-subscribers'));

echo $content;
