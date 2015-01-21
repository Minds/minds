<?php
/** 
 * Class to handle a Minds Node
 */

class MindsNode extends ElggObject{


	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "node";
		$this->attributes['launched'] = false;
		$this->attributes['expires'] = $this->expires();
	}

	/**
	 * Is this node allowed it's own domain
	 */
	public function allowedDomain(){ 
		return true;
//		return $this->getTier()->price > 0 ? true : false;//perhaps a little simplistic
	}

	/**
	 * Return tier applied to this node
	 */
	public function getTier(){
		$tier = get_entity($this->tier_guid, 'object');
		return $tier;
	}

	public function getURL(){
		if($this->launched){
			return 'http://'. $this->domain;
		}
	}
	
	/**
	 * Append the the relative users referred lists
	 * 
	 * @param string $username
	 */
	function setReferrer($username){
		$user = new \minds\entities\user($username);
		if(!isset($user->email))
			return false; //@todo better way to verify real user?
			
		$db = new \minds\core\data\indexes();
		return $db->insert('object:node:referrer:'.$user->guid, array($this->guid => $this->guid));
	}

	/**
	 * Check when the node expires
	 */
	public function expires(){
		//get the expiration for the current tier
		$expires = $this->getTier()->expires;
		if (!$expires) $expires = MINDS_EXPIRES_YEAR; // Default to year
		
		return $expires / (60 * 60 *24);
	}

	public function client($method = "GET", $domain = NULL, $data = array()){

		$ch = curl_init();
		//$data = http_build_query($data);
		$data['key'] = elgg_get_plugin_setting('manager_key', 'minds_nodes');
		switch (strtolower($method)) {
		    case 'post':
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				break;
		    case 'delete':
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); // Override request type
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				$domain .= '?' . http_build_query($data); //because post fields can not be sent with DELETE
				break;
		    case 'get':
		    default:
				curl_setopt($ch, CURLOPT_HTTPGET, true);
				$domain .= '?' . http_build_query($data);
				break;
		}

		curl_setopt($ch, CURLOPT_URL, elgg_get_plugin_setting('manager_addr', 'minds_nodes')."/v1/nodes/$domain");
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 2);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		//curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, "minds_nodes v1");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		
		$result = curl_exec($ch);
		$errors = curl_error($ch);
	
		return json_decode($result, true);	

	}
	/** 
	 * Check if the domain is already in use 
	 */
	public function checkDomain(){
		// Check whether node exists
		$request = $this->client('GET', $this->domain);
		if(!isset($request['error']))
		    throw new Exception("Sorry, domain $domain has already been registered"); // Exists
	}

	/** 
	 * Creates the multisite on the external server
	 */
	public function launchNode(){
		global $CONFIG;
		$this->checkDomain();
		$request = $this->client('POST', $this->domain, array('tier'=>$this->tier_guid, 'domain'=>$this->domain));
		if(isset($request['error']))
		    throw new Exception("Error creating database for the new minds node");
		$this->launched = true;
		return true;
	}

	/**
	 * Rename a node
	 */
	public function renameNode($new_domain){
		global $CONFIG;
		if(!$this->old_domain)
			$this->old_domain = $this->domain;
	
	//	$this->domain = 'heliski.minds.com';
	//	return $this->save();	
		if($new_domain == $this->domain)
			return true;

		$this->domain = $new_domain;
		$this->checkDomain(); //throws exception if in use
		
		$data = $this->client('POST', $this->old_domain .'/rename/'.$this->domain);

		$this->save();
	}

	/**
	 * Has payment been made?
	 */
	public function paid(){
		$order = $this->getOrder();
		if($order->status == 'Completed'){
			return true;
		}
		return false;
	}

	/** 
	 * Return the order related to this node
	 */
	public function getOrder(){
		$order = get_entity($this->order_guid, 'object');
		return $order;
	}

	/**
	 * Status check for node
 	 */
	public function checkNodeStatus(){

	}
	
	public function delete(){
		if(!$this->domain){
			throw new \Exception('The domain must be set');
		}
		parent::delete();
		
		return  $this->client('DELETE', $this->domain);
	}

	public function save($index = true) {
	    
	    if ($user = elgg_get_logged_in_user_entity()) {
		
		$cacher = \minds\core\data\cache\factory::build();
		$cacher->destroy("object::node::{$user->guid}");
		$cacher->destroy("object::node::{$user->guid}::count");
		
	    }
	    
	    return parent::save($index);
	}
			
	public function getExportableValues() {
                return array_merge(parent::getExportableValues(), array(
                        'domain',
                ));
        }
}
