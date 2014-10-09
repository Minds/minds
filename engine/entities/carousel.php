<?php
/**
 * Minds object entity. 
 * (this will replace the outdated Elgg entity system in the near future)
 */

namespace minds\entities;

class carousel extends object{
	
	/**
	 * Initialise attributes
	 * @return void
	 */
	public function initializeAttributes(){
		parent::initializeAttributes();
		$this->attributes = array_merge($this->attributes, array(
			'owner_guid' => elgg_get_logged_in_user_guid(),
			'access_id' => 2,
			'subtype' => 'carousel'
		));
	}
	
}
