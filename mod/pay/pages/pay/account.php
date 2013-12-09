<?php
/**
 * Pay - see account overview
 *
 * @package Pay
 */
elgg_load_library('elgg:pay');

//elgg_set_context('settings');

$username = get_input('username', elgg_get_logged_in_user_entity()->username);
$user = $username ? get_user_by_username($username) : elgg_get_logged_in_user_entity();

elgg_set_page_owner_guid($user->guid);

pay_breadcrumb();

elgg_push_breadcrumb(elgg_echo('pay:account'), 'pay/account');


$limit = get_input("limit", 10);

$title = elgg_echo('pay:account');
$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'pay',
	'limit' => $limit,
	'full_view' => FALSE,
	'owner_guid' => elgg_get_page_owner_guid(),
)); 
/*
$content = elgg_list_entities_from_metadata(array(
	'types' => 'object',
	'subtypes' => 'pay',
	'limit' => $limit,
	'full_view' => FALSE,
	'owner_guid' => elgg_get_page_owner_guid(),
	'metadata_name_value_pairs' => array('name' => 'order', 'value' => true),
));*/

if (!$content) {
	$content = elgg_echo('pay:account:none');
}

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
	'class' => 'pay'
));


echo elgg_view_page($title, $body);

?>
