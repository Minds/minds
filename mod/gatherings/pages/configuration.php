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
		
	
		$content = elgg_view('gatherings/conversations/revoke');
	
		$conversations = \minds\plugin\gatherings\start::getConversationsList();
		$layout = elgg_view_layout('one_sidebar_alt', array('content'=>$content, 'sidebar'=>elgg_view('gatherings/conversations/list', array('conversations'=>$conversations))));
		echo $this->render(array('body'=>$layout, 'class'=>'white-bg'));

	}
	
	/**
	 * Posting messages 
	 */
	public function post($pages){
		switch($pages[0]){
			case "keypair-1":
				
				if(!get_input('passphrase')){
					\register_error('You must enter a password');
					return $this->forward(REFERRER);
				}
				
				if(!elgg_is_xhr()){
					if(get_input('passphrase')	!= get_input('passphrase2')){
						\register_error('Sorry, your passwords didn\'t match');
						return $this->forward(REFERRER);
					}
				}
				
				$keypair = helpers\openssl::newKeypair(get_input('passphrase'));
				
				\elgg_set_plugin_user_setting('publickey', $keypair['public'], elgg_get_logged_in_user_guid(), 'gatherings');
				\elgg_set_plugin_user_setting('option', '1', elgg_get_logged_in_user_guid(), 'gatherings');
				\elgg_set_plugin_user_setting('privatekey', $keypair['private'], elgg_get_logged_in_user_guid(), 'gatherings');
				
				$new_pswd = base64_encode(openssl_random_pseudo_bytes(128));
				$tmp = helpers\openssl::temporaryPrivateKey($keypair['private'], get_input('passphrase'), $new_pswd);

				$_SESSION['tmp_privatekey'] = $tmp;
				$_SESSION['tmp_privatekey_ts'] = time();
		
				unset($_COOKIE['tmp_priv_pswd']);
	
				setcookie('tmp_priv_pswd', $new_pswd, time() + (60 * 60 * 60 * 24), '/', NULL, NULL, true);
				
				if(elgg_is_xhr()){
					echo $keypair['public']; exit;
				}
				break;
			case "keypair-2":
				$keypair = helpers\openssl::newKeypair();
				$content = 'Coming soon!';
				//is the user configured with a 
				

				exit;
				break;
			default:
		}
		$this->forward(elgg_get_site_url() . 'gatherings/conversations');
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
