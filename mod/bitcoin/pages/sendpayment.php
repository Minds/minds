<?php

gatekeeper();

if ($user = get_user_by_username(get_input('username'))) {
    
    // See if this is paying an order?
    $order = null;
    if ($order_guid = get_input('order_guid'))
	    $order = get_entity($order_guid);
    
    $title = 'Send a payment...';

    $body = elgg_view_layout("content", array(
	'title' => $title,
	'content' => elgg_view_form('bitcoin/send', null, array('wallet' => \minds\plugin\bitcoin\bitcoin()->getWallet($user), 'order' => $order)),
    ));

    echo elgg_view_page($title, $body);
} else
    forward();
    
    