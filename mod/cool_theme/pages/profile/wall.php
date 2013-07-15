<?php

$user = elgg_get_page_owner_entity();

if (!$user || !elgg_instanceof($user, 'user')) {
	register_error(elgg_echo('profile:notfound'));
	forward();
}

$title = $user->name;

$composer = '';
if (elgg_is_logged_in()) {
	$composer = elgg_view('page/elements/composer', array('entity' => $user));
}

$db_prefix = elgg_get_config('dbprefix');
$activity = elgg_list_river(array(
	'joins' => array("JOIN {$db_prefix}entities e ON e.guid = rv.object_guid"),
	'wheres' => array("e.container_guid = $user->guid OR rv.object_guid = $user->guid")
));

if (!$activity) {
	$activity = elgg_view('output/longtext', array('value' => elgg_echo('profile:activity:none')));
}

$body = elgg_view_layout('two_sidebar', array(
	'content' => $composer . $activity,
	'title' => $title,
));

echo elgg_view_page($title, $body);
