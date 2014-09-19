<?php
/**
 * Minds user entity. 
 * (this will replace the outdated Elgg entity system in the near future)
 */

namespace minds\entities;

use minds\core;

class user extends \ElggUser{
	
	public function subscribe(){
		$db = new core\data('friends');
	}
	
	public function unSubscribe(){
		
	}
	
}
