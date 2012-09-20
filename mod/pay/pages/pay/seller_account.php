<?php
/**
 * Pay - see account overview
 *
 * @package Pay
 */
elgg_load_library('elgg:pay');

//elgg_set_context('settings');

$username = get_input('username'); 
$user = $username ? get_user_by_username($username) : elgg_get_logged_in_user_entity();

elgg_set_page_owner_guid($user->guid);

pay_breadcrumb();

elgg_push_breadcrumb(elgg_echo('pay:account'), 'pay/account/'.$user->username);

elgg_push_breadcrumb(elgg_echo('pay:account:seller'), 'pay/account/seller');


$limit = get_input("limit", 10);

$title = elgg_echo('pay:account:seller');

$user->guid;
$content = elgg_list_entities_from_metadata(array(
	'types' => 'object',
	'subtypes' => 'pay',
	'limit' => $limit,
	'full_view' => FALSE,
	'metadata_name_value_pairs' => array(array('name' => 'seller_guid', 'value' => elgg_get_page_owner_guid())),
	
));

if(pay_get_user_balance($user->guid) > 0){

	elgg_register_menu_item('title', array(
		'name' => 'payment_withdraw',
		'text' => elgg_echo('pay:withdraw'),
		'href' => "#withdraw",
		'rel' => 'popup',
		'link_class' => 'elgg-button elgg-button-action elgg-lightbox',
	));
	
	$content .= elgg_view_module('popup', elgg_echo('pay:withdraw'), elgg_view_form('pay/withdraw'),
									 array('id'=> 'withdraw', 'class'=>'hidden pay-withdraw-module'));
	
	elgg_register_menu_item('title', array(
		'name' => 'seller_balance',
		'href' => '#',
		'text' => elgg_echo('pay:withdraw:balance', array(pay_get_user_balance($user->guid))),
		'link_class' => 'elgg-button elgg-button-action',
	));
}

if (!$content) {
	$content = elgg_echo('pay:account:none');
}

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $body);
?>