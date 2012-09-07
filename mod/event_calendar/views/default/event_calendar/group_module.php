<?php
/**
 * Group event calendar module
 */

$group = elgg_get_page_owner_entity();

if ($group->event_calendar_enable == "no") {
	return true;
}

elgg_push_context('widgets');
$content = elgg_view('event_calendar/groupprofile_calendar');
elgg_pop_context();

if (!$content) {	
	if (elgg_get_plugin_setting('group_always_display', 'event_calendar') == 'yes') {
    	$content = elgg_echo('event_calendar:no_events_found');
	}
}

if ($content) {
	$all_link = elgg_view('output/url', array(
		'href' => "event_calendar/group/$group->guid",
		'text' => elgg_echo('link:view:all'),
	));
	$new_link = elgg_view('output/url', array(
		'href' => "event_calendar/add/$group->guid",
		'text' => elgg_echo('event_calendar:new'),
	));
	echo elgg_view('groups/profile/module', array(
		'title' => elgg_echo('event_calendar:group'),
		'content' => $content,
		'all_link' => $all_link,
		'add_link' => $new_link,
	));
}
