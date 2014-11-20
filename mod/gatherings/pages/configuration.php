<?php
/**
 * Gatherings page handler
 */
namespace minds\plugin\gatherings\pages;

use minds\core;
use minds\interfaces;
use minds\plugin\gatherings\entities;
use minds\plugin\gatherings\helpers;

class configuration extends core\page implements interfaces\page{
	
	public $context = 'gatherings';
	
	/**
	 * Reading messages and getting lists of messages
	 */
	public function get($pages){
		
	
	
						
		$layout = elgg_view_layout('one_sidebar_alt', array('content'=>$content));
		echo $this->render(array('body'=>$layout));

	}
	
	/**
	 * Posting messages 
	 */
	public function post($pages){
		switch($pages[0]){
			case "keypair-1":
				$keypair = helpers\openssl::newKeypair(get_input('passphrase'));
				\elgg_set_plugin_user_setting('publickey', $keypair['public'], elgg_get_logged_in_user_guid(), 'gatherings');
				\elgg_set_plugin_user_setting('option', '1', elgg_get_logged_in_user_guid(), 'gatherings');
				\elgg_set_plugin_user_setting('privatekey', $keypair['private'], elgg_get_logged_in_user_guid(), 'gatherings');
				break;
			case "keypair-2":
				$keypair = helpers\openssl::newKeypair();
				$content = 'Coming soon!';
				//is the user configured with a 
				

				exit;
				break;
			default:
		}
		$this->forward(REFERRER);
	}
	
	/**
	 * Uploading content via messages (coming soon)
	 */
	public function put($pages){}
	
	/**
	 * Deleting messages
	 */
	public function delete($pages){
	}
	
}
