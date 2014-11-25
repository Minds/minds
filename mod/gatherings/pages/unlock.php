<?php
/**
 * Gatherings page handler
 */
namespace minds\plugin\gatherings\pages;

use minds\core;
use minds\interfaces;
use minds\plugin\gatherings\entities;
use minds\plugin\gatherings\helpers;
use minds\plugin\gatherings\counter;

class unlock extends core\page implements interfaces\page{
	
	
	public function get($pages){}
	

	public function post($pages){
		
			$password= get_input('password');
			
			$new_pswd = base64_encode(openssl_random_pseudo_bytes(128));
			
			$tmp = helpers\openssl::temporaryPrivateKey(\elgg_get_plugin_user_setting('privatekey', elgg_get_logged_in_user_guid(), 'gatherings'), $password, $new_pswd);
			
			if(!$tmp)
				throw new \Exception('wrong password supplied');
			
			$_SESSION['tmp_privatekey'] = $tmp;
			$_SESSION['tmp_privatekey_ts'] = time();
			
			setcookie('tmp_priv_pswd', $new_pswd, time() + (60 * 60 * 60 * 24), '/', NULL, NULL, true);
			
			exit;

	}
	

	public function put($pages){}

	public function delete($pages){}
	
}
