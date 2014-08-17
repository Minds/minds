<?php

global $CONFIG;


$ROOT_DOMAIN = $CONFIG->minds_multisite_root_domain;

// Work out which domain
$domain = get_input('domain');
$email = get_input('email');

$node_guid = get_input('node_guid');
$node = new MindsNode($node_guid);

$node->tier_guid = get_input('tier_guid');

if($tid = get_input('transaction_id')){
	$node->transaction_id = $tid;	
}

try {
    
    if(!$node)
		throw new Exception("This node is not registered");


    if ($domain) {

		//if(!$node->paid())
		//	throw new Exception('Payment is still needed before you can launch a node');

		$node->domain = $domain;
		if($node->launchNode()){//setups the node
			$node->save();
			    // And say we've used the order
	    		$order->payment_used = time();
	
	    	 	system_message("New minds network $domain successfully created!");
	        	forward(elgg_get_site_url() . "nodes/ping?domain=$domain");
		} 
 
    } else {
        throw new Exception("You must specify a node or domain");
    }
} catch (Exception $e) {
    register_error($e->getMessage());
}
