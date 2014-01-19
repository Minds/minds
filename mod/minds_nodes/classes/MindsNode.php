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
		return $this->getTier()->allowedDomain();
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
	 * Check when the node expires
	 */
	public function expires(){
		//get the expiration for the current tier
		$expires = $this->getTier()->expires;
		if (!$expires) $expires = MINDS_EXPIRES_YEAR; // Default to year
		
		return $expires / (60 * 60 *24);
	}

	/** 
	 * Check if the domain is already in use 
	 */
	public function checkDomain(){
		// Check whether node exists
		$exists = json_decode(file_get_contents($CONFIG->multisite_endpoint . 'webservices/get_domain_exists.php?domain=' . $domain));

		if (!$exists){
		    throw new Exception("Minds multisite could not be reached, please try again later");
		}
		if (!$exists->success){
		    throw new Exception($exists->message);
		}
		if ($exists->exists == true){
		    throw new Exception("Sorry, domain $domain has already been registered"); // Exists
		}
	}

	/** 
	 * Creates the multisite on the external server
	 */
	public function launchNode(){
		global $CONFIG;
		//do we really need to check domain, this function does that too...
		 // Register a node
		$results = json_decode(file_get_contents($CONFIG->multisite_endpoint . 'webservices/add_domain.php?domain=' . $this->domain . '&minds_user_id=' . $this->owner_guid . '&tier=' . $this->tier_guid));
		if (!$results){
		    throw new Exception("Minds multisite could not be reached while registering your domain, please try again later");
		}
		if (!$results->new_domain_id){ 
		    throw new Exception("Error creating database for the new minds node");
		}
		if ((!$results->tier) || ($results->tier!=$this->tier_guid)){
		    throw new Exception("Could not set tier $this->tier_guid on new minds node");
		}
		if (!$results->success){
		    throw new Exception($results->message);
		}
		$this->launched = true;
		return true;
	}

	/**
	 * Rename a node
	 */
	public function renameNode($new_domain){
		global $CONFIG;
		$results = json_decode(file_get_contents($CONFIG->multisite_endpoint . 'webservices/rename_domain.php?domain=' . $this->domain . '&minds_user_id=' . $this->owner_guid . '&new_domain='.$new_domain ));
                if (!$results){
                    throw new Exception("Minds multisite could not be reached while registering your domain, please try again later");
                }	
		$this->domain= $new_domain;
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

}
