<?php
/**
 * Pay - delete item from basket
 * Creates an object with subtype pay_basket
 *
 * @package Pay
 */

// Get variables
$item_guid = get_input('guid');
$item = get_entity($item_guid, 'object');


if($item->delete()){
	system_message(elgg_echo("pay:bakset:item:remove:success"));
} else {
	register_error(elgg_echo("pay:basket:item:remove:failed"));
}

forward('pay/basket');