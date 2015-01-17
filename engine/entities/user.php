<?php
/**
 * Minds user entity. 
 * (this will replace the outdated Elgg entity system in the near future)
 */

namespace minds\entities;

use Minds\Core;

class user extends \ElggUser{
	
	public function subscribe($guid, $data = array()){
		
		if(empty($data))
			$data = time();
		
		$friends = new core\Data\Call('friends');
		$friendsof = new core\Data\Call('friendsof');
		
		
		if(is_array($data))
			$data = json_encode($data);
		
		if($friends->insert($this->guid, array($guid=>$data)) && $friendsof->insert($guid, array($this->guid=>$data)))
			return true;
		
		return false;
	}
	
	public function unSubscribe($guid){
		
	}
	
	public function isSubscribed($guid){
		$db = new core\Data\Call('friends');
		if(key($db->getRow($this->guid, array('limit'=> 1, 'offset'=>$guid))) == $guid)
			return true;
		
		return false;
	}

	public function getSubscribersCount(){
		if($this->host){
			return 0;
		}

		$db = new core\Data\Call('friendsof');
		return (int) $db->countRow($this->guid);
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
	
	public function export(){
		$export = parent::export();
		$export['subscribed'] = elgg_get_logged_in_user_entity()->isSubscribed($this->guid);
		return $export;
	}	
}
