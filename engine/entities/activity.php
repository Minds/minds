<?php
/**
 * Minds activity entity. 
 */

namespace minds\entities;

class activity extends entity{
	
	/**
	 * Initialise attributes
	 * @return void
	 */
	public function initializeAttributes(){
		parent::initializeAttributes();
		$this->attributes = array_merge($this->attributes, array(
			'type' => 'activty',
			'owner_guid' => elgg_get_logged_in_user_guid(),
			'access_id' => 0, //private, 
			
			'node' => elgg_get_site_url()
		));
	}
	
	
}
