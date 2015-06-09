<?php
/**
 * Minds user entity. 
 * (this will replace the outdated Elgg entity system in the near future)
 */

namespace minds\entities;

use Minds\Core;
use Minds\Helpers;

class user extends \ElggUser{
    
    /**
     * Sets and encrypts a users email address
     * @param $email
     * @return $this
     */
    public function setEmail($email){
        global $CONFIG; //@todo use object config instead
        $this->email = base64_encode(Helpers\OpenSSL::encrypt($email, file_get_contents($CONFIG->encryptionKeys['email']['public'])));
        return $this;
    }
    
    /**
     * Returns and decrypts an email address
     * @return $this
     */
    public function getEmail(){
        global $CONFIG; //@todo use object config instead
        return Helpers\OpenSSL::decrypt(base64_decode($this->email), file_get_contents($CONFIG->encryptionKeys['email']['private']));
    }

    public function subscribe($guid, $data = array()){
	   return \Minds\Helpers\Subscriptions::subscribe($this->guid, $guid, $data);
    }
	
    public function unSubscribe($guid){
        return \Minds\Helpers\Subscriptions::unSubscribe($this->guid, $guid, $data);		
    }

    public function isSubscriber($guid){
        $cacher = Core\Data\cache\factory::build();

        if($cacher->get("$this->guid:isSubscriber:$guid"))
            return true;
        if($cacher->get("$this->guid:isSubscriber:$guid") === 0)
            return false;

        $return = 0;
        $db = new Core\Data\Call('friendsof');
    	$row = $db->getRow($this->guid, array('limit'=> 1, 'offset'=>$guid));
        if($row && key($row) == $guid)
            $return = true;

        $cacher->set("$this->guid:isSubscriber:$guid", $return);

        return $return; 
    }
	
	public function isSubscribed($guid){
        $cacher = Core\Data\cache\factory::build();

        if($cacher->get("$this->guid:isSubscribed:$guid"))
            return true;
        if($cacher->get("$this->guid:isSubscribed:$guid") === 0)
           return false;
        
        $return = 0;
        $db = new Core\Data\Call('friends');
		$row = $db->getRow($this->guid, array('limit'=> 1, 'offset'=>$guid));
		if($row && key($row) == $guid)
			$return = true;
		
        $cacher->set("$this->guid:isSubscribed:$guid", $return);

		return $return ;
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
            'website',
            'briefdescription',
            'dob',
            'gender',
            'city'
        ));
    }

}
