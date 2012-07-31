<?php
/**
 * Elgg livestreaming plugin everyone page
 *
 */

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner) {
	forward('livestreaming/all');
}

elgg_push_breadcrumb($page_owner->name);

$content .= elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'livestreaming',
	'container_guid' => $page_owner->guid,
	'limit' => 10,
	'full_view' => false,
	'view_toggle_type' => false
));

if (!$content) {
	$content = elgg_echo('livestreaming:none');
}

$title = elgg_echo('livestreaming:owner', array($page_owner->name));

$filter_context = '';
if ($page_owner->getGUID() == elgg_get_logged_in_user_guid()) {
	$filter_context = 'mine';
}

$sidebar = elgg_view('livestreaming/sidebar');
$vars = array(
	'filter_context' => $filter_context,
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
);

// don't show filter if out of filter context
if ($page_owner instanceof ElggGroup) {
	$vars['filter'] = false;
}

$body = elgg_view_layout('content', $vars);

echo elgg_view_page($title, $body);
