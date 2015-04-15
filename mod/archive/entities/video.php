<?php
/**
 * A minds archive video entity
 * 
 * Handles basic communication with cinemr
 */
namespace minds\plugin\archive\entities;

use minds\entities\object;
use cinemr;
use Minds\Helpers;

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
				'account_guid' => '335988155444367360',
                        	'secret' => '+/rW1ArsueEjXK++0zkxlBrbLkb5suHqvqZJ64kX8rk=',
				'uri' => 'http://cinemr.minds.com'
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
		$cacher = \Minds\Core\Data\cache\factory::build();
		if($return = $cacher->get("$this->guid:transcode:$transcode"))
			return $return;
		
		$cinemr = $this->cinemr();
		$expires = time() + ((60*60*60)*24*7*4);
		if($this->access_id == 0)
			$expires = time() + (60*60*60);
		$url =  $cinemr::factory('media')->get($this->cinemr_guid."/transcodes/$transcode", $expires);
		$cacher->set("$this->guid:transcode:$transcode", $url, 1440);
		return $url;
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
		$domain = elgg_get_site_url();
		global $CONFIG;
		if(isset($CONFIG->cdn_url))
			$domain = $CONFIG->cdn_url;

		//if($this->thumbnail){
			return $domain . 'archive/thumbnail/'.$this->guid.'/'.$this->thumbnail.'/3';
		//} else {
		//	$cinemr = $this->cinemr();
       	       // 	return $cinemr::factory('media')->get($this->cinemr_guid.'/thumbnail');
		//}
	}

	public function getURL(){
		return elgg_get_site_url() . 'archive/view/'.$this->guid;
	}

	/**
	 * Extend the default entity save function to update the remote service
	 * 
	 */
	public function save($force = false){
		$this->super_subtype = 'archive';
		parent::save((!$this->guid || $force));
		
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

	 public function getExportableValues() {
                return array_merge(parent::getExportableValues(), array(
                        'thumbnail',
			'cinemr_guid',
		));
	}

	/**
	 * Extend exporting
	 */
	public function export(){
		$export = parent::export();
		$export['thumbnail_src'] = $this->getIconUrl();
		$export['src'] = array(
			'360.mp4' => $this->getSourceUrl('360.mp4'),
			'720.mp4' => $this->getSourceUrl('720.mp4')
        );
        $export['thumbs:up:count'] = Helpers\Counters::get($this->guid,'thumbs:up');
        $export['thumbs:down:count'] = Helpers\Counters::get($this->guid,'thumbs:down');
		return $export;
	}
}
