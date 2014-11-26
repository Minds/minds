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

class decrypt extends core\page implements interfaces\page{
	
	public $context = 'gatherings';
	private $passphrase = NULL;
	
	public function get($pages){

		
	}

	public function post($pages){
		
		$message = get_input('message');
		$passphrase = isset($_COOKIE['tmp_priv_pswd']) ? $_COOKIE['tmp_priv_pswd'] : NULL;
		
		$private_key = isset($_SESSION['tmp_privatekey']) ? $_SESSION['tmp_privatekey'] : \elgg_get_plugin_user_setting('privatekey', elgg_get_logged_in_user_guid(), 'gatherings');
		$option = \elgg_get_plugin_user_setting('option', elgg_get_logged_in_user_guid(), 'gatherings');
		if($private_key && (int) $option == 1){
			$message = helpers\openssl::decrypt(base64_decode($message), $private_key, $passphrase);
		} 
		
		echo $message;
		
		exit;
	
	}

	public function put($pages){}
	

	public function delete($pages){}
	
}
