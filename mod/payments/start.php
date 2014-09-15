<?php
/**
 * Payments
 */

namespace minds\plugin\payments;

use minds\bases;
use minds\core;

class start extends bases\plugin{
	
	public function init(){
		
		\elgg_extend_view('css/elgg', 'css/payments');
		
		/**
		 * Register our page end points
		 */
		$path = "minds\\plugin\\payments\\pages";
		core\router::registerRoutes(array(
				'/settings/payments/methods' => "$path\\methods",
				'/settings/payments/payouts' => "$path\\payouts",
				'/settings/payments/transactions' => "$path\\transactions",
			));
	
		\elgg_register_event_handler('pagesetup', 'system', array($this, 'pageSetup'));

	}
	
	
	/**
	 * Page setup (menus etc)
	 */
	public function pageSetup(){
		if(elgg_get_context() == 'settings'){
			
			\elgg_register_menu_item('page', array(
			    'name' => 'payments:methods',
			    'text' => 'Payment Methods',
			    'href' => 'settings/payments/methods',
			    'title' => elgg_echo('payments:methods')
		    ));
			
			\elgg_register_menu_item('page', array(
			    'name' => 'payments:payouts',
			    'text' => 'Payout Preferences',
			    'href' => 'settings/payments/payouts',
			 ));
			 
			 \elgg_register_menu_item('page', array(
			    'name' => 'payments:transactions',
			    'text' => 'Transactions',
			    'href' => 'settings/payments/transactions',
			 ));
		}
	}
	
}
