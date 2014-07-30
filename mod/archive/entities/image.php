<?php
/**
 * Image entity
 */
namespace minds\plugin\archive\entities;

use minds\entities;

class image extends entities\file{
	
			
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['super_subtype'] = 'archive';
		$this->attributes['subtype'] = "image";
	}
	
	public function getIconUrl($size = 'large'){
		global $CONFIG; //@todo remove globals!
		return $CONFIG->cdn_url . 'archive/thumbnail/' . $this->guid;
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
		
		//remove from the filestore
	}
	
	/**
	 * Return the folder in which this image is stored
	 */
	public function getFilePath(){
		return str_replace($this->getFilename(), '', $this->getFilenameOnFilestore());
	}
	
	
	public function upload($file){
				
		if(!$this->guid){
			$g = new \GUID();
			$this->guid = $g->generate();
		}
		
		if(!$this->filename){
			$dir = $this->getFilenameOnFilestore() . "/image/$this->container_guid/$this->guid";
			if (!file_exists($dir)) {
				mkdir($dir, 0755, true);
			}
		}
		
		$this->filename = "image/$this->container_guid/$this->guid/".$file['name'];
		
		$filename = $this->getFilenameOnFilestore();
		$result = move_uploaded_file($file['tmp_name'], $filename);

		if (!$result) {
			return false;
		}

		return $result;
	}

	 public function getExportableValues() {
                return array_merge(parent::getExportableValues(), array(
                        'thumbnail',
			'cinemr_guid',
		));
	}
}
