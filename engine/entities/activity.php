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
	
	/*public function save($index = true){
		
		
		
	}*/
	
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
	
}
