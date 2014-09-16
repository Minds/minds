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
				
		$encrypt = new core\encrypt('AC314ED28313654E4751C525C44EF4A7A92434D1801F2BAFD7597FA27DED5FDC');
		return $this->encrypted = $encrypt->encrypt($data);
		
	}
	
	public function getEncrypted(){
		
		$encrypt = new core\encrypt('AC314ED28313654E4751C525C44EF4A7A92434D1801F2BAFD7597FA27DED5FDC');
		return $encrypt->decrypt($this->encrypted);
		
	}
	

}