<?php
/**
 * Minds activity entity. 
 */

namespace minds\entities;

class activity extends entity{
	
	/**
	 * Initialise attributes
	 * @return void
	 */
	public function initializeAttributes(){
		parent::initializeAttributes();
		$this->attributes = array_merge($this->attributes, array(
			'type' => 'activity',
			'owner_guid' => elgg_get_logged_in_user_guid(),
			'access_id' => 2, //private, 
			
			'node' => elgg_get_site_url()
		));
	}
	
	/**
	 * Returns an array of indexes into which this entity is stored
	 * 
	 * @param bool $ia - ignore access
	 * @return array
	 */
	protected function getIndexKeys($ia = false){

		$indexes = array( 
			$this->type
		);

		$owner = $this->getOwnerEntity();	
		
		/** Get the followers **/
		$followers = in_array($this->access_id, array(2, -2, 1)) ? $owner->getFriendsOf(null, 10000, "", 'guids') : array();
		if(!$followers) $followers = array(); 
		$followers = array_keys($followers);
		
		array_push($indexes, "$this->type:user:$owner->guid");
		
		array_push($followers, $this->owner_guid);
		
		foreach($followers as $follower)
			array_push($indexes, "$this->type:network:$follower");

		/**
		 * @todo make it only post to a group if we are in a group
		 */
		array_push($indexes, "$this->type:container:$this->container_guid");

		return $indexes;
	}
	
	public function getExportableValues(){
		return array_merge(parent::getExportableValues(),
			array(
				'title', 
				'blurb',
				'perma_url',
				'message',
				'thumbnail_src',
				'remind_object'
			));
	}
	
	/**
	 * Set the message
	 * @param string $message
	 * @return $this
	 */
	public function setMessage($message){
		$this->message = $message;
		return $this;
	}
	
	/**
	 * Sets the title
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title){
		$this->title = $title;
		return $this;
	}
	
	/**
	 * Sets the blurb
	 * @param string $blurb
	 * @return $this
	 */
	public function setBlurb($blurb){
		$this->blurb = $blurb;
		return $this;
	}
	
	/**
	 * Sets the url
	 * @param string $url
	 * @return $this
	 */
	public function setURL($url){
		$this->perma_url = $url;
		return $this;
	}
	
	/**
	 * Sets the thumbnail
	 * @param string $src
	 * @return $this
	 */
	public function setThumbnail($src){
		$this->thumbnail_src = $src;
		return $this;
	}
	
	/**
	 * Sets the owner
	 * @param mixed $owner
	 * @return $this
	 */
	public function setOwner($owner){
		if(is_numeric($owner)){
			$owner = new \minds\entities\user($owner);
			$owner = $owner->export();
		}
		
		$this->owner = $owner;
		
		return $this;
	}
	
	/**
	 * Set from a local minds object
	 * @return $this
	 */
	public function setFromEntity($entity){
		return $this;
	}
	
	/**
	 * Set the reminded object
	 * @param array $array - the exported array
	 * @return $this
	 */
	public function setRemind($array){
		$this->remind_object = $array;
		return $this;
	}
	
	/**
	 * Set a custom, arbitrary set. For example a custom video view, or maybe a set of images. I envisage
	 * certain service could extend this.
	 * @param string $type
	 * @param array $data
	 * @return $this
	 */
	public function setCustom($type, $data = array()){
		$this->custom_type = $type;
		$this->custom_data = $data;
		return $this;
	}
}
