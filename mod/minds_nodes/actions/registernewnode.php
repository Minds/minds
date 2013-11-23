<?php

global $CONFIG;


$ROOT_DOMAIN = $CONFIG->minds_multisite_root_domain;

// Work out which domain
$domain_at_minds = get_input('domain_at_minds');
$my_domain = get_input('my_domain');
$email = get_input('email');

$node_guid = get_input('node_guid');
$node = get_entity($node_guid, 'object');

try {
    
    if(!$node){
	throw new Exception("This node is not registered");
    }

    if (!$email)
        throw new Exception("Email address must be entered.");
    
    if ($domain_at_minds || $my_domain) {

        // Work out which domain
        $domain = $domain_at_minds . $ROOT_DOMAIN;
        if ($my_domain)
            $domain = $my_domain;

	if(!$node->paid()){
		throw new Exception('Payment is still needed before you can launch a node');
	}

	$node->domain = $domain;
	if($node->launchNode()){//setups the node
		$node->save();
		        // And say we've used the order
    		$order->payment_used = time();

    	 	system_message("New minds network $domain successfully created!");
        	forward(elgg_get_site_url() . "register/testping?domain=$domain");
	} 
 
    } else {
        throw new Exception("You must specify a node or domain");
    }
} catch (Exception $e) {
    register_error($e->getMessage());
}
