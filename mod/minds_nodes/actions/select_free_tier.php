<?php

// "Buy" a free tier


gatekeeper();

$ia = elgg_set_ignore_access();

if ($tier = get_entity(get_input('tier_id','object'))) {
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
    
    $order_guid = $order->save();


	//now create a blank MindsNode ... don't launch yet though...
        if (($node_guid = get_input('node_guid')) && ($node = get_entity($node_guid))) {
            // If we're upgrading an existing node, then we use that instead
            error_log("Looks like we're upgrading an existing node with a new order.");
        }
        else {
            $node = new MindsNode();
            $node->owner_guid = elgg_get_logged_in_user_guid();
            $node->launched = false;
        }
	$node->tier_guid = $tier->guid;
	$node->order_guid = $order_guid;	
	$node->foo = bar;    
	$node->save();

	//forward to the manage page
	forward('nodes/manage');
}
