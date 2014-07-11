<?php
/**
 * Comments entity
 */
 
namespace minds\plugin\comments\entities;

use minds\entities;

class comment extends entities\entity{
	
	public function initializeAttributes(){
		parent::initializeAttributes();
		$this->attributes = array_merge($this->attributes, array(
			'type' => 'comment',
			'owner_guid'=>elgg_get_logged_in_user_guid()
		));
	}
	
	
	public function save(){

		parent::save(false);
		$indexes = new \minds\core\data\indexes('comments');
		$indexes->set($this->parent_guid, array($this->guid=>$this->guid));
		return $this->guid;
	}
	
	public function delete(){
		$db = new \minds\core\data\call('entities');
		$db->removeRow($this->guid);
		
		$indexes = new \minds\core\data\indexes('comments');
		$indexes->remove($this->parent_guid, array($this->guid));
		return true;
	}
	
	public function view(){
		echo \elgg_view('comment/default', array('entity'=>$this));
	}
	
}
