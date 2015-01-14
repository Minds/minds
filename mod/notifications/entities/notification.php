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
		
		$db = new \Minds\Core\Data\Call('entities_by_time');
		$db->insert('notifications:'.$this->to_guid, array($this->guid => $this->guid));

		\minds\plugin\notifications\notifications::increaseCounter($this->to_guid);

		return $guid;
	}
	
	public function delete(){
		
		parent::delete();
		
		$db = new \Minds\Core\Data\Call('entities_by_time');
		$db->removeAttributes('notifications:' . $this->to_guid, array($this->guid));
		
	}
	
	public function getExportableValues(){
		return array_merge(parent::getExportableValues(),
			array(
				'object_guid',
				'from_guid',
				'notification_view',
				'params'
			));
	}
	
}