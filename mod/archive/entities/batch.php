<?php
/**
 * Batch entity
 * 
 * A batch is initiated from the uploader and contains 
 */
namespace minds\plugin\archive\entities;

use minds\entities\object;
use Minds\Core\data;

class batch extends object{
	
	public function __construct($guid = NULL){
		if($guid){
			$this->loadFromGUID($guid);
		}
	}
	
	public function addToList($guid){
		$index = new Data\indexes('batch');
		return $index->set($this->guid, array($guid=>$guid));
	}
	
	/**
	 * Return  the list of guids attached to this batch. 
	 * FYI doing a direct db instead of loading this entity might make sense.
	 */
	public function getList($limit=10000000){
		$index = new Data\indexes('batch');
		return $index->get($this->guid, array('limit'=>$limit));
	}

	/**
	 * Extend the default entity save function to update the remote service
	 * 
	 */
	public function save(){
		$this->super_subtype = 'archive';
		parent::save(true);
		return $this->guid;
	}
	
	/**
	 * Extend the default delete function to remove from the remote service
	 */
	public function delete(){
		parent::delete();
	}
	
}
