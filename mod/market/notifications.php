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

class notifications extends bases\plugin{
	
	static public function send($params){
		return \elgg_send_email(elgg_get_site_entity()->email, $params['to'], $params['subject'], $params['body']);
	}
	
	static public function sendTOSeller($seller, $subject, $body){
		
	}
	
	static public function sendTOBuyer($buyer, $subject, $body){
		
	}

}
