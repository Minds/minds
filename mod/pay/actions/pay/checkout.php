<?php
/**
 * Pay - chout
 *
 * @package Pay
 */
elgg_load_library('elgg:pay');

$user_guid = elgg_get_logged_in_user_guid();
$user = get_entity($user_guid);

$basket = elgg_get_entities(array(
								'type' => 'object',
								'subtype' => 'pay_basket',
								'owner_guid' => $user_guid,
								));
								
$amount = pay_basket_total();
$recurring = false;				

//We create a new order object								
$order = new ElggObject();
$order->subtype = 'pay';

$order->order = true;

//temp variables
$order->seller_guid = $basket[0]->seller_guid;
$order->object_guid = $basket[0]->object_guid;

foreach($basket as $item){
	$a->title = $item->title;
	$a->description = $item->description;
	$a->price = $item->price;
	$a->quantity = $item->quantity;
	$a->object_guid = $item->object_guid;
	$a->seller_guid = $item->seller_guid;
        if ($item->recurring == 'y') // TODO: Currently we have to set whole basket to recurring if one item repeats. Not idea.
            $recurring = true;
	$items[] = $a;
	$item->delete();
}
$order->items = serialize($items);

$order->amount = $amount;
$order->status = 'created';

// Flag as recurring
if ($recurring)
    $order->recurring = true;

$order->access_id = 1;

$order->payment_method = 'paypal';

if($order->save()){
	notification_create(array($order->seller_guid, $order->getOwnerGUID()), 0, $order->getGuid(), array('notification_view'=>'pay_order'));
	
	return pay_call_payment_handler($order->payment_method, array( 'order_guid' => $order->getGuid(),
            'user_guid' => $user_guid,
            'amount' => $amount,
            'recurring' => $recurring
        ));
} else {
	register_error(elgg_echo("pay:checkout:failed"));
}
