<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace minds\plugin\bitcoin_tipjar;

use minds\core;
use \minds\plugin\bitcoin;

class tipjar extends \ElggPlugin     
{
    public static $tipjar;
    
    public function __construct(){
	    parent::__construct('bitcoin_tipjar');

	    $this->init();
    }
     
    /**
     * Log a transaction.
     * @param \ElggUser $from User who sent the payment
     * @param \ElggUser $to User who received the payment
     * @param type $amount Amount
     * @param bool $used_minds_account True if $to didn't have a receive address, so the tip was stored using the Minds central bitcoin account.
     */
    protected function logTip(\ElggUser $from, \ElggUser $to, $amount, $used_minds_account = false) {
	
	$entry = new \ElggObject();
	$entry->subtype = 'tip';
	$entry->access_id = ACCESS_PRIVATE;
	
	$entry->owner_guid = $from->guid;
	$entry->to_guid = $to->guid;
	$entry->amount = $amount;
	
	$entry->sent_via_minds = $used_minds_account;
	
	return $entry->save();
    }
    
    /**
     * Tip a user
     * @param \ElggUser $user
     * @param type $amount Amount in bitcoins
     */
    public function tip(\ElggUser $to, $amount) {
	
	$using_system_address = false;
	$receive_address = bitcoin\bitcoin()->getReceiveAddressForUser($to);
	$user = elgg_get_logged_in_user_entity();
	
	if (!$user) throw new \Exception("Sorry, you need to be logged in");
	
	if (!$receive_address)
	{
	    // Save against minds, but account for it internally
	    $using_system_address = true;
	    $receive_address = bitcoin\bitcoin()->getSystemReceiveAddress();    
	}
	
	if (!$receive_address)
	    throw new \Exception("No receive address could be found or created, sorry!");
	
	
	$wallet = bitcoin\bitcoin()->getWallet($user);
	if (!$wallet) throw new \Exception("No wallet found, why not create one?");
	
	// Send an log the payment
	if (bitcoin\bincoin()->sendPayment($wallet->guid, $receive_address, $amount)) {
	    $this->logTip($user, $to, $amount, $using_system_address);
	}
	
	return false;
    }
    
    /**
     * Initialise tipjar
     */
    public function init() {
	
	// When a new wallet is created for a user, generate a receive address for the user
	elgg_register_event_handler('create', 'object', function($event, $object_type, $object) {
	    if (elgg_instanceof($object, 'object', 'bitcoin_wallet')) {
		$ia = elgg_set_ignore_access();
		bitcoin\bitcoin()->createReceiveAddressForUser(get_user($object->owner_guid));
		elgg_set_ignore_access($ia);
	    }
	});
    }
}

/**
 * Helper function to retrieve current bitcoin handler
 * @return \minds\plugin\bitcoin_tipjar\tipjar
 */
function &tipjar()
{
    return \minds\plugin\bitcoin_tipjar\tipjar::$tipjar;
}