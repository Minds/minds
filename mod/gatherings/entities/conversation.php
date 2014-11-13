<?php
/**
 * Gatherings conversation entity
 * 
 */
 
namespace minds\plugin\gatherings\entities;

use minds\entities\object;
use minds\plugin\gatherings\helpers;

class conversation extends object{
	
	/**
	 * Get messages
	 */
	public function getMessages($limit = 12, $offset = ''){
		
	}
	
	/**
	 * Encrypts the object 'message
	 */
	public function encrypt(){
		helpers\openssl::encrypt('string', $public_key);
	}
	
	public function decrypt(){
		helpers\openssl::encrypt('string', $private_key);
	}
	
}