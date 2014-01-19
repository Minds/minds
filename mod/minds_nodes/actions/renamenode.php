<?php

global $CONFIG;

$domain = get_input('domain');
$email = get_input('email');

$node_guid = get_input('node_guid');
$node = get_entity($node_guid, 'object');

try {
    
    if(!$node){
	throw new Exception("This node is not registered");
    }


    if($domain){

	if(!$node->paid()){
		throw new Exception('Payment is still needed before you can launch a node');
	}


	if($node->renameNode($domain)){//setups the node
    	 	system_message("Rename done");
	} 
 
    } else {
        throw new Exception("You must specify a node or domain");
    }
} catch (Exception $e) {
    register_error($e->getMessage());
}
