<?php
/**
 * View an order
 *
 * @package Pay
 */

elgg_load_library('elgg:pay');

pay_breadcrumb();

$order = get_entity(get_input('guid'), 'object');


elgg_push_breadcrumb(elgg_echo('pay:account'), 'pay/account');
elgg_push_breadcrumb(elgg_echo('pay:account:order') . ': '. $order->guid, 'pay/account/order/' . $order->guid);

$title = $file->title;

$content = elgg_view_entity($order, array('full_view' => true));
$content .= elgg_view_comments($order);

if($order->status == 'awaitingpayment'){
	elgg_register_menu_item('title', array(
		'name' => 'payment',
		'text' => elgg_echo('pay:payment:' . $order->payment_method),
		'href' => "pay/payment/$order->guid",
		'link_class' => 'elgg-button elgg-button-action',
	));
}

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'class' => 'pay'
));

echo elgg_view_page($title, $body);
