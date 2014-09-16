<?php
/**
 * Encrypted tax form
 */
 
namespace minds\plugin\payments\entities;
 
use minds\entities;
use minds\core;

class taxForm extends entities\object{
	
	/**
	 * Initialise attributes
	 * @return void
	 */
	public function initializeAttributes(){
		parent::initializeAttributes();
		$this->attributes = array_merge($this->attributes, array(
			'subtype' => 'taxForm',
			'owner_guid' => elgg_get_logged_in_user_guid(),
			'access_id' => 0 //private
		));
	}
	
	public function setEncrypted($data){
		global $CONFIG;				
		$encrypt = new core\encrypt($CONFIG->encryption_keys_tax_form);
		return $this->encrypted = $encrypt->encrypt($data);
		
	}
	
	public function getEncrypted(){
		global $CONFIG;		
		$encrypt = new core\encrypt($CONFIG->encryption_keys_tax_form);
		return $encrypt->decrypt($this->encrypted);
		
	}
	

}
