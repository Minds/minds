<?php
/**
 * Gatherings message entity model
 * 
 * A message contains information about a message in a thread. 
 */
 
namespace minds\plugin\gatherings\entities;


class message extends gathering{
	
	protected function initializeAttributes(){
		$this->attributes = array_merge($this->attributes, array(
			'access_id' => ACCESS_PRIVATE,
			'owner_guid'=> \elgg_get_logged_in_user_guid(),
		));
	}
	
	/**
	 * Override the default indexes
	 */
	protected function getIndexKeys($ia = false){
		return array(
			"object:gathering:$this->gathering_guid"
		);
	}
	
	public function save($timebased = true){
		$this->encrypt();
		parent::save($timebased);
	}
	
	/**
	 * Encrypt the message
	 */
	public function encrypt(){
		
	}
	
}

