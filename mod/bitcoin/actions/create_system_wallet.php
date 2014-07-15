<?php

    $return = array(
	'status' => 0,
	'system_messages' => array('error' => array(), 'success' => array()),
	'output' => ''
    );

    try {
	
	$password = get_input('password');
	
	if (!$password) throw new Exception ('No password given!');
	
	$wallet_guid = minds\plugin\bitcoin\bitcoin ()->createSystemWallet($password);
	$wallet = get_entity($wallet_guid);

	if (!$wallet)
	    throw new Exception ("Wallet could not be created...");
	    
	system_message("New system wallet created!");
	$return['output'] = $wallet->wallet_address;

    } catch (Exception $ex) {
	
	$return['status'] = -1;
	$return['system_messages']['error'][] = $ex->getMessage();
	
    }
    
    header('Content-Type: application/json');
    echo json_encode($return); exit;