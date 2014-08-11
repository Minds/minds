<?php
/**
 * Minds Bitcoin support
 */
 
namespace minds\plugin\bitcoin;

use minds\core;

abstract class bitcoin extends \ElggPlugin     
{
    public static $bitcoin;
    
    public function __construct($plugin) {
	parent::__construct($plugin);
	
	bitcoin::$bitcoin = $this;
    }
    
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
	
	// Register CSS
	elgg_register_css('bitcoin.css', elgg_get_simplecache_url('css', 'bitcoin'));
		
	// Create bitcoin handler
	elgg_register_page_handler('bitcoin', function($pages){
	
	    elgg_load_css('bitcoin.css');
	    
	    switch ($pages[0]) {
		case 'wallet':
		case 'mywallet' :
			set_input('username', $pages[1] ? $pages[1] : elgg_get_logged_in_user_entity()->username);
			require_once(dirname(__FILE__) . '/pages/wallet.php');
		    break;
		case 'send' :
			set_input('username', elgg_get_logged_in_user_entity()->username);
			require_once(dirname(__FILE__) . '/pages/sendpayment.php');
		    break;
		case 'settings' : 
			require_once(dirname(__FILE__) . '/pages/settings.php');
		    break;
	    }
	    
	    return true;
	});
	
	// Bitcoin wallet menu
	if (elgg_is_logged_in()) {
	    elgg_register_menu_item('site', array(
		    'name' => 'bitcoin',
		    'text' => '<span class="entypo">&#59406;</span> My Wallet', //'My Wallet', // TODO: Replace me with a nice graphic
		    'href' => 'bitcoin/mywallet',
		    'title' => elgg_echo('bitcoin'),
		    'priority' => 10
	    ));
	}
	
	// Listen to user settings save
	elgg_register_action('bitcoin/settings/save', dirname(__FILE__) . '/actions/plugins/settings/save.php', 'admin');
	elgg_register_admin_menu_item('minds', 'bitcoin');	
	elgg_register_admin_menu_item('minds', 'setup', 'bitcoin');  
	
	elgg_register_action('bitcoin/usersettings/save', dirname(__FILE__) . '/actions/plugins/usersettings/save.php');
	elgg_register_event_handler('pagesetup', 'system', function() {
            elgg_register_menu_item("page", array(
                'name' => 'bitcoin',
                'text' => elgg_echo('bitcoin:settings'),
                'href' => 'bitcoin/settings',
                'contexts' => array('bitcoin')
            ));
	});
	
	// Payment action
	elgg_register_action('bitcoin/send', dirname(__FILE__) . '/actions/sendpayment.php');
	elgg_register_action('bitcoin/unlock', dirname(__FILE__) . '/actions/unlockwallet.php');
	
	
	// Create a wallet for every new user
	elgg_register_plugin_hook_handler('register', 'user', function($hook, $type, $value, $params) {
	    $ia = elgg_set_ignore_access(); // We're not logged in yet, so get_entity will fail without this
	    
	    try {
		$user = elgg_extract('user', $params);
		
		$password = generate_random_cleartext_password();
		
		$new_wallet = bitcoin()->createWallet($user, $password);
	
		if ($new_wallet) $new_wallet = get_entity($new_wallet);
		if (!$new_wallet) throw new \Exception("Could not generate a wallet for the new user...");
		if (!$new_wallet->wallet_address) throw new \Exception("There was no address linked with wallet {$new_wallet->guid}");
		
		// Now, notify a user
		notify_user($user->guid, elgg_get_site_entity()->guid, 'New bitcoin wallet created', "Welcome to minds! We have taken the liberty of creating a bitcoin wallet for your account, the password for which is $password"); // TODO: Change message
		
		// grant new user some bitcoins
		if ($satoshi = elgg_get_plugin_setting('satoshi_to_new_user', 'bitcoin')) {
		    if ($wallet_guid = elgg_get_plugin_setting('central_bitcoin_wallet_object_guid', 'bitcoin')) {
			$result = bitcoin()->sendPayment($wallet_guid, $new_wallet->wallet_address, $satoshi);
			if (!$result)
				throw new \Exception("There was a problem granting satoshi to {$new_wallet->wallet_address}");
			
			error_log("Bitcoin: Successfully set starting amount for {$user->guid} to $satoshi");
		    } else 
			error_log("BITCOIN: No system bitcoin address!");
		} else 
		    error_log("BITCOIN: No satoshi value set!");
	    } catch (\Exception $ex) {
		error_log("BITCOIN: " . $ex->getMessage());
	    }
	    
	    $ia = elgg_set_ignore_access($ia);
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