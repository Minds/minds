<?php
/**
 * Market notifications helpers
 *  * 
 * @package Minds.Core
 * @subpackage Plugins
 * @author Mark Harding (mark@minds.com)
 */

namespace minds\plugin\market;

use minds\core;
use minds\bases;
use minds\entities\user;

class notifications extends bases\plugin{
	
	static public function send($params){
		return \elgg_send_email(elgg_get_site_entity()->email, $params['to'], $params['subject'], $params['body']);
	}
	
	/**
	 * Sends an email to the seller
	 * 
	 */
	static public function sendTOSeller($seller, $subject, $body){
		if(is_numeric($seller))
			$seller = new user($seller);
		
		return self::send(array(
			'to'=>$seller->email,
			'subject'=>$subject,
			'body'=>$body
		));
	}
	
	static public function sendTOBuyer($buyer, $subject, $body){
		if(is_numeric($buyer))
			$buyer = new user($buyer);
		
		return self::send(array(
			'to'=>$buyer->email,
			'subject'=>$subject,
			'body'=>$body
		));
	}

}
