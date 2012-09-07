<?php
elgg_load_library('elgg:event_calendar');
$site_calendar = elgg_get_plugin_setting('site_calendar', 'event_calendar');
$group_calendar = elgg_get_plugin_setting('group_calendar', 'event_calendar');
$admin = elgg_is_admin_logged_in();
$containers = array();
if (($site_calendar != 'no') && ($admin || !$site_calendar || ($site_calendar == 'loggedin'))) {
	$containers[0] = elgg_echo('event_calendar:site_calendar');
}
$user = elgg_get_logged_in_user_entity();
$groups = $user->getGroups('',0,0);
foreach ($groups as $group) {
	if (event_calendar_activated_for_group($group)) {
		if ($admin || !$group_calendar || $group_calendar == 'members') {
			if ($group->canWriteToContainer($user->guid)) {
				$containers[$group->guid] = $group->name;
			}
		} else if ($group->canEdit()) {
			$containers[$group->guid] = $group->name;
		}
	}
}
if ($vars['container_guid']) {
	$value = $vars['container_guid'];
} else {
	$value = 0;
}
echo elgg_view('input/dropdown',array('name'=>'group_guid', 'value'=>$vars['container_guid'],'options_values'=>$containers));
