<?php

// "Buy" a free tier


gatekeeper();

$ia = elgg_set_ignore_access();

if ($tier = get_entity(get_input('tier_id'))) {
    $order = new ElggObject();
    $order->subtype = 'pay';

    $order->order = true;

    //temp variables
    $order->seller_guid = elgg_get_site_entity()->guid;
    $order->object_guid = $tier->guid;

    // Don't think we need this as we're just creating a stub order
/*
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
$order->items = serialize($items); */

    $order->amount = 0;
    $order->status = 'Completed';

    $order->access_id = 1;

    $order->payment_method = 'n/a';
    
    $order->recurring = true;
    
    $order->save();
    
}