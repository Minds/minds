<?php
/**
 * Pay - pay order object view
 *
 * @package Pay
 */
elgg_load_library('elgg:pay'); 

$full = elgg_extract('full_view', $vars, FALSE);
$order = elgg_extract('entity', $vars, FALSE);

$user = get_entity($order->owner_guid, 'user');

if (!$order) {
	return TRUE;
}

$user_guid = elgg_get_logged_in_user_guid();
//check to see if the user is either the owner, seller or and admin
if($user_guid == $order->owner_guid || $user_guid == $order->seller_guid || elgg_is_admin_logged_in()){
	
} else {
	return true;
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'pay',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

if($full){
	
	$icon = elgg_view_entity_icon($user, 'small');
	echo elgg_view_image_block($icon, elgg_view('output/url', array('href'=> $user->getURL(), 'text'=> $user->name)));
	
	echo '<div>';
	echo elgg_echo('pay:account:order:status') . elgg_echo('pay:account:order:status:' .$order->status);
	echo '</div><br/>';
	
	$items = unserialize($order->items);
	$currency = pay_get_currency();
	
	foreach($items as $item){
		$object = get_entity($item->object_guid);
		echo '<div>';
			echo '<b>' . elgg_view('output/url', array('text'=> $item->title, 'href'=>$object->getURL())) . '</b> ';
			echo '<i>x' . $item->quantity . '</i> ';
			echo  '' . $currency['symbol'] . $item->price;
		echo '</div>';	
	}
	echo '<br/><div><b>Total: </b>' . $currency['symbol'] . $order->amount . '</div>';

	
	
} else {

	if($order->withdraw){
		$title = elgg_view('output/url', array('text' => elgg_echo('pay:withdraw:title') . ': ' . $order->guid, 'href'=>$order->getUrl()));
	}elseif($order->order){
		$title = elgg_view('output/url', array('text' => elgg_echo('pay:account:order') . ': ' . $order->guid, 'href'=>$order->getUrl()));
	} else {
		$title = 'undefined - please ask admin for more info.';
	}
	$params = array(
		'entity' => $order,
		'metadata' => $metadata,
		'title' => $title,
		'subtitle' => elgg_view('output/url', array('text' => $user->name, 'href'=>$user->getUrl())) . ' | ' . elgg_get_friendly_time($order->time_created),
		'content' => '',
	);
	
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);
	$icon = elgg_view_entity_icon($user, 'small');
	echo elgg_view_image_block($icon, $list_body);

}