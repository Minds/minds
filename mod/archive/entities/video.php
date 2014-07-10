<?php
/**
 * A minds archive video entity
 * 
 * Handles basic communication with cinemr
 */
namespace minds\plugin\archive\entities;

use minds\entities\object;
use cinemr;

class video extends object{
	
	private $cinemr;
	
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['super_subtype'] = 'archive';
		$this->attributes['subtype'] = "video";
	}

	
	public function __construct($guid = NULL){
		parent::__construct($guid);	
	}
	
	public function cinemr(){
		return new cinemr\sdk\client(array(
				'account_guid' => '332928421254402048',
				'secret' => 'Ux4wa95LWfvQZV3e5py6XJqN5fadpunyXRJu14odLeE=',
				'uri' => 'http://cinemr.minds.io'
			));
	}

	/**
	 * Get the status of the video
	 */
	public function getStatus(){
		$cinemr = $this->cinemr();
                $data = $cinemr::factory('media')->get($this->cinemr_guid);
		return $data['status'];
	}
	
	/**
	 * Return the source url of the remote video 
	 * @param string $transcode 
	 * @return string
	 */
	public function getSourceUrl($transcode = '720.mp4'){
		$cinemr = $this->cinemr();
		return $cinemr::factory('media')->get($this->cinemr_guid."/transcodes/$transcode");
	}
	
	/**
	 * Uploads to remote
	 * 
	 */
	 
	public function upload($filepath){
		$cinemr = $this->cinemr();
		$data = $cinemr::factory('media')->put(NULL, $filepath);
		$this->cinemr_guid = $data['guid'];
	}

	public function getIconUrl(){
		if($this->thumbnail){
			return elgg_get_site_url() . 'archive/thumbnail/'.$this->guid.'/'.$this->last_updated;
		} else {
			$cinemr = $this->cinemr();
       	        	return $cinemr::factory('media')->get($this->cinemr_guid.'/thumbnail');
		}
	}

	/**
	 * Extend the default entity save function to update the remote service
	 * 
	 */
	public function save(){
		parent::save(true);
		
		$cinemr = $this->cinemr();
		$cinemr::factory('media')->post($this->cinemr_guid, array(
				'title' => $this->title,
				'description' => $this->description,
				'minds_guid' => $this->guid,
				'minds_owner' => $this->owner_guid
			));
		return $this->guid;
	}
	
	/**
	 * Extend the default delete function to remove from the remote service
	 */
	public function delete(){
		parent::delete();
		
		$cinemr = $this->cinemr();
		$cinemr::factory('media')->delete($this->cinemr_guid);
	}
	
}
