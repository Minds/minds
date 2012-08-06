<?php
/**
 * Wall index page
 *
 * @package Wall
 */

$page_owner_guid = get_input('page_owner_guid', elgg_get_logged_in_user_guid());
elgg_set_page_owner_guid($page_owner_guid);
$page_owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb($page_owner->name, $page_owner->getURL());

$options = array(
	'types' => 'object',
	'subtypes' => 'wallpost',
	'limit' => 10,
	'metadata_name_value_pairs' => array('name'=>'to_guid', 'value'=> $page_owner_guid),
	'reverse_order_by' => false,
	'full_view'=>false
);

$title = elgg_echo('wall:owner', array($page_owner->name));


elgg_push_breadcrumb(elgg_echo('wall:title'), $mb_url);

if ($history_user) {
	elgg_push_breadcrumb($history_user->name);
}

$content = elgg_view_form('wall/add',array('name'=>'elgg-wall'),array('to_guid'=>$page_owner_guid));
$content .= elgg_list_entities_from_metadata($options);

if (!$content) {
	$content = elgg_echo('messageboard:none');
}

$vars = array(
	'filter' => false,
	'content' => $content,
	'title' => $title,
	'reverse_order_by' => true
);

$body = elgg_view_layout('content', $vars);

echo elgg_view_page($title, $body);