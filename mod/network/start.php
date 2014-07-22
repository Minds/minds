<?php
/**
 * Minds network
 */
namespace minds\plugin\network;

use minds\bases;
use minds\core;

class start extends bases\plugin{
	
	public function __construct(){
		parent::__construct('network');	

		$this->init();
	}
	
	public function init(){
		//register a cron script to periodically check the network
		
		//extend the PAM system so that we can check other nodes in the network against the credentials passed
		
		//once a news post is made, send this to the users subscribers by pinging their sites. 
		
		//list out for incoming pings
		
		//subscribers hook, allow external subscriptions
	}
	
}
