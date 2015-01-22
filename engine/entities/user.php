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

	public function getSubscribersCount(){
		if($this->host){
			return 0;
		}

		$cacher = \minds\core\data\cache\factory::build();
		if($cache = $cacher->get("$this->guid:friendsofcount")){
			return $cache;
		}
		
		$db = new core\data\call('friendsof');
		$return = (int) $db->countRow($this->guid);
		$cacher->set("$this->guid:friendsofcount", $return);
		return $return;
	}
	
	/**
	 * Set the secret key for clusters to use
	 * 
	 * @todo - should we use oauth2 instead. should this be stored in its own row rather than in the user object?
	 * 
	 * @param string $host
	 */
	public function setSecretKey($host){
		$key = "secret:" . serialize($host);
		$this->$key = core\clusters::generateSecret();
		$this->save();
	}
	
	
}
