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
	if (bitcoin\bincoin()->sendPayment($wallet->guid, $receive_address, bitcoin\bitcoin()->toBTC($amount))) {
	    $this->logTip($user, $to, $amount, $using_system_address);
	}
	
	return false;
    }
    
    /**
     * Initialise tipjar
     */
    public function init() {
	
	// Register the tip action
	elgg_register_action('tipjar/tip', dirname(__FILE__) . '/actions/send_tip.php');
	
	// Register CSS
	elgg_register_css('tipjar.css', elgg_get_simplecache_url('css', 'tipjar'));
		
	// When a new wallet is created for a user, generate a receive address for the user
	elgg_register_event_handler('create', 'object', function($event, $object_type, $object) {
	    if (elgg_instanceof($object, 'object', 'bitcoin_wallet')) {
		$ia = elgg_set_ignore_access();
		if ($object->owner_guid) { // Don't create a tipjar for system users
		    bitcoin\bitcoin()->createReceiveAddressForUser(get_user($object->owner_guid), array('istipjar' => 'y'));
		}
		elgg_set_ignore_access($ia);
	    }
	});
	
	// Listen to new receive payment
	elgg_register_plugin_hook_handler('payment-received', 'blockchain', function($hook, $type, $return, $params) {
	    
	    if ($params['get_variables']['istipjar'] && ($user = $params['user'])) {
	
		if ($params['user']) {
		    
		    // See if we have a bitcoin address to pay the user for - transfer will have already been handled by blockchain when this callback is triggered, so we just have to notify the user
		    if ($address = elgg_get_plugin_user_setting('bitcoin_address', $user->guid, 'bitcoin')) {
			notify_user($user->guid, elgg_get_site_entity()->guid, "New payment in your tipjar!", "You have received a tip of {$params['value_in_btc']} bitcoins to your bitcoin address ($address).");
		    } else {
			notify_user($user->guid, elgg_get_site_entity()->guid, "New payment in your tipjar!", "You have received a tip of {$params['value_in_btc']} bitcoins, but since you don't have a bitcoin account registered we're holding on to it for you. Please contact us to find out more!");
		    }
		
		    return true;
		} 
		
	    }
	});
	
	// Tipjar page handler
	elgg_register_page_handler('tipjar', function($pages){
	
	    elgg_load_css('tipjar.css');
	    
	    switch ($pages[0]) {
		case 'mytips' :
			set_input('username', elgg_get_logged_in_user_entity()->username);
			require_once(dirname(__FILE__) . '/pages/mytips.php');
		    break;
		case 'tip' :
		    if ($pages[1]) {
			set_input('username', $pages[1]);
			require_once(dirname(__FILE__) . '/pages/tip.php');
		    }
		    else forward();
		    break;
	    }
	    
	    return true;
	});
	
	// Tipjar
	if (elgg_is_logged_in()) {
	    elgg_register_plugin_hook_handler('register', 'menu:user_hover', function($hook, $type, $return, $params) {

		    if (elgg_instanceof($params['entity'], 'user')) {

			    if ($params['entity']->guid == elgg_get_logged_in_user_guid()) 
				return $return;

			    $url = "tipjar/tip/{$params['entity']->username}";
			    $item = new \ElggMenuItem('tip', elgg_echo('tip'), $url);
			    $return[] = $item;
		    }

		    return $return;
	    });
	}
	/*if (elgg_is_logged_in()) {
	    elgg_register_menu_item('site', array(
		    'name' => 'tipjar',
		    'text' => 'My Tipjar', // TODO: Replace me with a nice graphic
		    'href' => 'tipjar/mytips',
		    'title' => elgg_echo('mytipjar'),
		    'priority' => 10
	    ));
	}*/
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