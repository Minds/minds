<?php
/**
 * Section
 */
 
namespace minds\plugin\cms\entities;
 
use minds\entities;

class section extends entities\object{

	public $version = 1;
	
	/**
	 * Initialise attributes
	 * @return void
	 */
	public function initializeAttributes(){
		parent::initializeAttributes();
		$this->attributes = array_merge($this->attributes, array(
			'subtype' => 'cms_section',
			'owner_guid' => elgg_get_logged_in_user_guid(),
			'access_id' => 2 //pages are public
		));
	}
	
	/**
	 * Returns an array of indexes into which this entity is stored
	 * 
	 * @param bool $ia - ignore access
	 * @return array
	 */
	protected function getIndexKeys($ia = false){
		return array(
			"$this->type:cms:sections:$this->group",
			"$this->type:cms:sections"
		);
	}

}
