<?php
/**
 * Section
 */
 
namespace minds\plugin\cms\entities;
 
use minds\plugin\cms\exceptions;
use minds\entities;
use minds\core\data;

class page extends entities\object{
	
	/**
	 * Initialise attributes
	 * @return void
	 */
	public function initializeAttributes(){
		parent::initializeAttributes();
		$this->attributes = array_merge($this->attributes, array(
			'subtype' => 'cms_page',
			'owner_guid' => elgg_get_logged_in_user_guid(),
			'access_id' => 2, //pages are public,
			'context' => 'footer',
			'uri'=>NULL
		));
	}
	
	public function __construct($guid = NULL){
		if(is_string($guid) && !is_numeric($guid)){
			$guids = data\indexes::fetch("object:cms:page:$guid", array('limit'=>1));
			if(!$guids)
				throw new exceptions\notfound($guid);
			
			$guid = key($guids);
		}
		
		return parent::__construct($guid);
	}
	
	/**
	 * Returns an array of indexes into which this entity is stored
	 * 
	 * @param bool $ia - ignore access
	 * @return array
	 */
	protected function getIndexKeys($ia = false){
		return array(
			"$this->type:cms:page:$this->uri",
		);
	}
	
	public function save($ia = false){
		$guid = parent::save($ia);
		
		$lu = new data\lookup();
		$lu->set("object:cms:menu:$this->context", array($this->uri => "$this->title"));

		return $guid;
	}
	
	public function setTitle($title){
		$this->title = $title;
		return $this;
	}
	
	public function setBody($body){
		$this->body = $body;
		return $this;
	}
	
	public function setUri($uri){
		$this->uri = $uri;
		return $this;
	}

}