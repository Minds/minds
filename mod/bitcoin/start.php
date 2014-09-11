<?php
/**
 * Bitcoin support.
 * 
 *  
 * @package Minds.Core
 * @subpackage Plugins
 * @author Marcus Povey <http://www.marcus-povey.co.uk>
 */

namespace minds\plugin\bitcoin;

use minds\bases;
use minds\core;

class start extends bases\plugin{
	
	public function init(){
		
		\elgg_extend_view('css/elgg', 'css/bitcoin');
		
		/**
		 * Register our page end points
		 */
		$path = "minds\\plugin\\bitcoin";
		core\router::registerRoutes(array(
				'/bitcoin' => "$path\\pages\\index",
				'/bitcoin/wallet' => "$path\\pages\\wallet",
				'/bitcoin/send' => "$path\\pages\\send",
				'/bitcoin/receive' => "$path\\pages\\receive"
			));
		
		
		/**
		 * We send some bitcoin to users
		 */
		//\elgg_register_plugin_hook_handler('register', 'user', array($this, 'userRegistration'));
		
		/**
		 * Register a site menu 
		 * @todo make this oop friendly
		 */
		\elgg_register_menu_item('site', array(
		    'name' => 'bitcoin',
		    'text' => '<span class="entypo">&#59408;</span> My Wallet',
		    'href' => 'bitcoin/wallet',
		    'title' => elgg_echo('bitcoin')
	    ));

		\elgg_register_event_handler('pagesetup', 'system', array($this, 'pageSetup'));

		
		\elgg_register_action('bitcoin/settings/save', dirname(__FILE__) . '/actions/plugins/settings/save.php', 'admin');
		\elgg_register_admin_menu_item('minds', 'bitcoin');	
		\elgg_register_admin_menu_item('minds', 'setup', 'bitcoin');  

	}
	
	
	/**
	 * Page setup (menus etc)
	 */
	public function pageSetup(){
		if(elgg_get_context() == 'bitcoin'){
			
			\elgg_register_menu_item('page', array(
			    'name' => 'bitcoin',
			    'text' => '<span class="entypo">&#59408;</span> My Wallet',
			    'href' => 'bitcoin/wallet',
			    'title' => elgg_echo('bitcoin')
		    ));
			
			\elgg_register_menu_item('page', array(
			    'name' => 'bitcoin:send',
			    'text' => 'Send',
			    'href' => 'bitcoin/send',
			    'title' => elgg_echo('bitcoin:send')
		    ));
		}
	}
	
	/**
	 * Initial user registration hook
	 * 
	 * @todo needs to be rethought
	 */
	public function userRegistration($hook, $type, $value, $params){
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
	}

	public static function toBTC($satoshi) {
		 return (float)($satoshi / 100000000);
	}
	
	public static function toSatoshi($btc) {
		 return $btc * 100000000; 
	}
	
}
