<?php
/**
 * Transaction entity
 */
 
namespace minds\plugin\bitcoin\entities;
 
use Minds\Entities;
use minds\plugin\bitcoin\services\blockchain;

class transaction extends Entities\Object{
	
	/**
	 * Initialise attributes
	 * @return void
	 */
	public function initializeAttributes(){
		parent::initializeAttributes();
		$this->attributes = array_merge($this->attributes, array(
			'subtype' => 'bitcoin_transaction',
			'owner_guid' => elgg_get_logged_in_user_guid(),
			'access_id' => 0 //private
		));
	}
	

}