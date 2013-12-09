<?php
/**
 * Pay - add to basket action file
 * Creates an object with subtype pay_basket
 *
 * @package Pay
 */

// Get variables
$type_guid = get_input("type_guid");
$type = get_entity($type_guid, 'object');

$title = get_input("title", $type->title ? $type->title : $type->name);
$desc = get_input("description", $type->description);
$price = get_input("price", $type->price);
$quantity = get_input("quantity", 1);
$user_guid = (int) elgg_get_logged_in_user_guid();
$seller_guid = get_input("seller_guid", $type->owner_guid);
$recurring = get_input("recurring", 'n');

$item = new ElggObject();
$item->type = 'object';
$item->subtype = 'pay_basket';

$item->object_guid = $type_guid;
$item->title = $title;
$item->description = $desc;
$item->quantity = $quantity;
$item->price = $price*$quantity;
$item->object_guid = $type_guid;
$item->seller_guid = $seller_guid;
$item->owner_guid = $user_guid;
$item->access_id = 1; 

if($item->save()){
    
        $item->recurring = $recurring; 

	//system_message(elgg_echo("pay:bakset:item:add:success"));
} else {
	register_error(elgg_echo("pay:basket:item:add:failed"));
}

forward('pay/basket');