<?php
/**
 * Minds Bitcoin support
 */
 
namespace minds\plugin\bitcoin;

use minds\core;

abstract class bitcoin extends \ElggPlugin {
    public static $bitcoin;

    
    /**
     * Get the base for the API Call
     */
    abstract protected function getAPIBase();
    
    /**
     * Retrieve a wallet for a user.
     */
    abstract public function getWallet(\ElggUser $user);
    
    /**
     * Create a wallet for a user.
     * @return guid|false
     */
    abstract public function createWallet(\ElggUser $user, $password);
    
    /**
     * Create a system wallet.
     */
    abstract public function createSystemWallet($password);
    
    /**
     * Import a wallet from a third party provider.
     * 
     * @param $wallet_uuid The wallet address on the third party system
     * @param $user User who owns the wallet, or currently logged in user if not provided.
     */
    abstract public function importWallet($wallet_guid, $address, $password = null, \ElggUser $user = null, $system = false);
    
    /**
     * Get the addess(s) associated with a wallet
     */
    abstract public function getAddressesFromWallet($wallet_guid);
    
    /**
     * When passed a wallet GUID (as stored in Elgg), will return it's current balance in BTC.
     */
    abstract public function getWalletBalance($wallet_guid);
    
    /**
     * Send a payment from a wallet to a bitcoin address.
     */
    abstract public function sendPayment($from_wallet_guid, $to_address, $amount_in_satoshi);
    
    /**
     * Temporarily unlock a given wallet, by storing its password for a short period of time
     */
    abstract public function unlockWallet($wallet_guid, $password);




    /**
     * Create receive address for user
     * 
     * @param $user the user
     * @param $params Optional parameters added on to an address to identify it (e.g. handlers or labels)
     * @param $btc_address to use as trigger, if not specified this will be extracted from user settings.
     */
    abstract public function createReceiveAddressForUser(\ElggUser $user, array $params = null, $btc_address = null);
    
    /**
     * Get the receive address for user.
     */
    abstract public function getReceiveAddressForUser(\ElggUser $user);
    
    /**
     * Create receive address for the system
     */
    abstract public function createSystemReceiveAddress();
    
    /**
     * Get the receive address for the system.
     */
    abstract public function getSystemReceiveAddress();
    
    /**
     * Convert a value into bitcoins
     */
    abstract public function convertToBTC($amount, $currency = 'USD');
    
    
    /**
     * Convert a bitcoin to satoshi value
     */
    public static function toSatoshi($btc) { return $btc * 100000000; }
    /**
     * Convert a satoshi value to bitcoin
     */
    public static function toBTC($satoshi) { return (float)($satoshi / 100000000); }
    
    
    public function logReceived($from_address, $to_user, $amount_satoshi) {
	
		$ia = elgg_set_ignore_access();
		
		$obj = new \ElggObject();
		$obj->subtype = 'bitcoin_transaction';
		$obj->owner_guid = $to_user->guid;
		$obj->access_id = ACCESS_PRIVATE;
		
		$obj->from_address = $from_address;
		$obj->amount_satoshi = $amount_satoshi;
		$obj->action = 'received';
		
		$id = $obj->save();
		
		error_log("Bitcoin: Logged an incoming transaction $id: From {$from_address} to {$to_user->name} of $amount_satoshi");
		
		$ia = elgg_set_ignore_access($ia);
		
		return $id;
    }
    
    public function logSent($from_user, $to_address, $amount_satoshi) {
	
		$ia = elgg_set_ignore_access();
		
		$obj = new \ElggObject();
		$obj->subtype = 'bitcoin_transaction';
		$obj->owner_guid = $from_user->guid;
		$obj->access_id = ACCESS_PRIVATE;
		
		$obj->to_address = $to_address;
		$obj->amount_satoshi = $amount_satoshi;
		$obj->action = 'sent';
		
		$id = $obj->save();
		
		error_log("Bitcoin: Logged an outgoing transaction $id: From {$from_user->name} to $to_address of $amount_satoshi");
		
		$ia = elgg_set_ignore_access($ia);
		
		return $id;
    }
    
    
    
    /**
     * Initialise
     */
    public function init() {
		
		elgg_register_action('bitcoin/usersettings/save', dirname(__FILE__) . '/actions/plugins/usersettings/save.php');
		elgg_register_event_handler('pagesetup', 'system', function() {
	            elgg_register_menu_item("page", array(
	                'name' => 'bitcoin',
	                'text' => elgg_echo('bitcoin:settings'),
	                'href' => 'bitcoin/settings',
	                'contexts' => array('bitcoin')
	            ));
		});
	
    }
    
    
    
    
}

/**
 * Helper function to retrieve current bitcoin handler
 * @return \minds\plugin\bitcoin\bitcoin
 */
function &bitcoin()
{
    return \minds\plugin\bitcoin\bitcoin::$bitcoin;
}