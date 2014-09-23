<?php
/**
 * Minds user entity. 
 * (this will replace the outdated Elgg entity system in the near future)
 */

namespace minds\entities;

use minds\core;

class user extends \ElggUser{
	
	public function subscribe($guid, $data = array()){
		
		if(empty($data))
			$data = time();
		
		$friends = new core\data\call('friends');
		$friendsof = new core\data\call('friendsof');
		
		
		if(is_array($data))
			$data = json_encode($data);
		
		if($friends->insert($this->guid, array($guid=>$data)) && $friendsof->insert($guid, array($this->guid=>$data)))
			return true;
		
		return false;
	}
	
	public function unSubscribe($guid){
		
	}
	
	public function isSubscribed($guid){
		$db = new core\data\call('friends');
		if(key($db->getRow($this->guid, array('limit'=> 1, 'offset'=>$guid))) == $guid)
			return true;
		
		return false;
	}
	
}
