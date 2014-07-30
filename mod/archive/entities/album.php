<?php
/**
 * Album entity
 * 
 * Albums are containers for other entities and also act as PAM controllers 
 */
namespace minds\plugin\archive\entities;

use minds\entities\object;
use minds\core\data;

class album extends object{
		
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['super_subtype'] = 'archive';
		$this->attributes['subtype'] = "album";
	}
	
	/**
	 * Get the icon url. This is configurable to be multiple images from the album or
	 * just a specific image. It defaults the the latest image in the album
	 */
	public function getIconURL($size = 'large'){
		return null;
	}
	
	public function getChildrenGuids(){
		$index = new data\indexes('object:container');
		return $index->get($this->guid);
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
		//delete all children too.
	}
	
	function getFilePath(){
	}

	 public function getExportableValues() {
		return array_merge(parent::getExportableValues(), array(
			'thumbnail'
		));
	}
}
