<?php
/**
 * Pay - update item in basket
 *
 * @package Pay
 */

// Get variables
$item_guid = get_input('item_guid');
$item = get_entity($item_guid, 'object');

$quantity = round(get_input('quantity'));


if($item->original_price){
	$price = $item->original_price * $quantity;
} else {
	$price = $item->price * $quantity;
	$item->original_price = $item->price;
}

$item->quantity = $quantity;

//keep a log of the original price

$item->price = $price;


if($item->save()){
	system_message(elgg_echo("pay:bakset:item:update:success"));
} else {
	register_error(elgg_echo("pay:basket:item:update:failed"));
}

forward('pay/basket');