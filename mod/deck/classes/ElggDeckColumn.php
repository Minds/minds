<?php
/**
 * Extended class of ElggObject for managing columns
 * 
 */
class ElggDeckColumn extends ElggObject {
	
	static $name;
	
	public function __construct($guid){
		parent::__construct($guid);
		
		$name = elgg_echo('deck_river:method:'.$this->method);
		if(strpos($name, 'page/') !== FALSE){
			$parts = explode('/',$name);
			$name = $this->getAccount()->getSubAccount($parts[1])->name;
		}
		
		$this->name = $name;
		$this->colour = $this->getAccount()->column_colour;
	}

	/**
	 * Set the super subtype
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "deck_column";
	}

	/**
	 * Returns the ElggDeckTab entity this column belongs to
	 */
	public function getTab(){
		$tab = get_entity($this->tab_guid);
		return $tab;
	}
	
	public function addToTab($tab_guid){
		$tab = get_entity($tab_guid);
		if(!$tab){
			throw new Exception('The tab can not be found');
		}
		if(!$this->guid){
			throw new Exception('This function should be called after save. A GUID could not be found.');
		}
		$tab->addColumn($this->guid);
		$this->tab_guid = $tab->guid;
		$this->save();
	}
	
	/**
	 * Return the ElggDeckColumn entity set for this column
	 */
	public function getAccount(){
		$account = get_entity($this->account_guid);
		return $account;
	}
	
	/**
	 * Get the data for the feed
	 */
	public function getRiverData(array $params = array()){
		$settings = $this->getSettings();
		$method = $settings->method;
		$data = $this->getAccount()->getData($method, $params);
		return $data;
	}
	
	/**
	 * Returns settings
	 */
	public function getSettings(){
		return $this;
	}
}
