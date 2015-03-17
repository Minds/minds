<?php
/**
 * Minds user entity. 
 * (this will replace the outdated Elgg entity system in the near future)
 */

namespace minds\entities;

use Minds\Core;

class user extends \ElggUser{

    public function subscribe($guid, $data = array()){
	return \Minds\Helpers\Subscriptions::subscribe($this->guid, $guid, $data);
    }
	
    public function unSubscribe($guid){
        return \Minds\Helpers\Subscriptions::unSubscribe($this->guid, $guid, $data);		
    }

    public function isSubscriber($guid){
        $db = new Core\Data\Call('friendsof');
	$row = $db->getRow($this->guid, array('limit'=> 1, 'offset'=>$guid));
        if($row && key($row) == $guid)
            return true;
        
        return false; 
    }
	
	public function isSubscribed($guid){
		$db = new Core\Data\Call('friends');
		$row = $db->getRow($this->guid, array('limit'=> 1, 'offset'=>$guid));
		if($row && key($row) == $guid)
			return true;
		
		return false;
	}

	public function getSubscribersCount(){
		if($this->host){
			return 0;
		}

		$cacher = Core\Data\cache\factory::build();
		if($cache = $cacher->get("$this->guid:friendsofcount")){
			return $cache;
		}
		
		$db = new Core\Data\Call('friendsof');
		$return = (int) $db->countRow($this->guid);
		$cacher->set("$this->guid:friendsofcount", $return);
		return $return;
	}

    public function getSubscriptonsCount(){
        if($this->host){
            return 0;
        }

        $cacher = Core\Data\cache\factory::build();
        if($cache = $cacher->get("$this->guid:friendscount")){
            return $cache;
        }

        $db = new Core\Data\Call('friends');
        $return = (int) $db->countRow($this->guid);
        $cacher->set("$this->guid:friendscount", $return);
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
	
	public function export(){
		$export = parent::export();
		if(Core\session::isLoggedIn()){
            $export['subscribed'] = elgg_get_logged_in_user_entity()->isSubscribed($this->guid);
            $export['subscriber'] = elgg_get_logged_in_user_entity()->isSubscriber($this->guid);
        }
        $export['subscribers_count'] = $this->getSubscribersCount();
        $export['subscriptions_count'] = $this->getSubscriptionsCount();
		return $export;
	}	

    public function getExportableValues() {
        return array_merge(parent::getExportableValues(), array(
            'website'
        ));
    }

}
