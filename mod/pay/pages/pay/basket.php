<?php
/**
 * View the basket
 *
 * @package Pay
 */
 
elgg_load_library('elgg:pay');

$user_guid = elgg_get_logged_in_user_guid();
$user = get_entity($user_guid, 'user');

elgg_set_context('pay_basket');

pay_breadcrumb();

elgg_push_breadcrumb(elgg_echo('pay'), 'pay');
elgg_push_breadcrumb(elgg_echo('pay:basket'), 'pay/basket');

$title = elgg_echo('pay:basket');

$content = pay_get_basket();

if(pay_basket_total() > 0){

/*elgg_register_menu_item('title', array(
	'name' => 'checkout',
	'text' => elgg_echo('pay:checkout'),
	'href' => "action/pay/checkout",
	'link_class' => 'elgg-button elgg-button-action',
	'is_action' => true,
));*/
//for now we are just going to forward to the payment
forward(elgg_add_action_tokens_to_url('action/pay/checkout'));
}



$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'class' => 'pay'
));

echo elgg_view_page($title, $body);
