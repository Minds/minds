<?php
/**
 * Notification entity
 */
 
namespace minds\plugin\notifications\entities;

use minds\entities;

class notification extends entities\entity{
	
	public function initializeAttributes(){
		parent::initializeAttributes();
		$this->attributes = array_merge($this->attributes, array(
			'type'=>'notification'
		));
	}
	
	
	public function save(){

		$guid = parent::save(false);
		
		$db = new \minds\core\data\call('entities_by_time');
		$db->insert('notifications:'.$this->to_guid, array($this->guid => $this->guid));

		\minds\plugin\notifications\notifications::increaseCounter($this->to_guid);

		return $guid;
	}
	
	public function delete(){
		
		parent::delete();
		
		$db = new \minds\core\data\call('entities_by_time');
		$db->remove('notifications:' . $this->to_guid, array($this->guid));
		
	}
	
}