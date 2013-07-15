<?php
// turn this into a core function
global $autofeed;
$autofeed = true;

$group = elgg_get_page_owner_entity();
if (!$group instanceof ElggGroup) {
	forward('groups/all');
}

elgg_load_library('elgg:groups');
groups_register_profile_buttons($group);

$content = elgg_view('groups/profile/layout', array('entity' => $group));
if (group_gatekeeper(false)) {
	$sidebar = elgg_view('groups/sidebar/members', array('entity' => $group));
} else {
	$sidebar = '';
}

$body = elgg_view_layout('two_sidebar', array(
	'content' => $content,
	'sidebar_alt' => $sidebar,
	'title' => $group->name,
));

echo elgg_view_page($group->name, $body);