<?php
/**
 * Extended class of ElggObject for managing tabs
 * 
 */
class ElggDeckTab extends ElggObject {

	public function __construct($guid=null){
		parent::__construct($guid);
	}

	/**
	 * Set the super subtype
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "deck_tab";
	}

	/**
	 * Returns the ElggDeckTab entity this column belongs to
	 */
	public function getColumns(){
		$column_guids = json_decode($this->column_guids);
		foreach($column_guids as $guid){
			$columns[] = get_entity($guid);
		}
		
		usort($columns, function($a,$b){ return $a->position - $b->position;});
		
		return $columns;
	}
	
	/**
	 * Adds a columns
	 */
	public function addColumn($guid){
		$column_guids = $this->column_guids ? json_decode($this->column_guids) : array();
		if(!in_array($guid, $column_guids)){
			array_push($column_guids, $guid);
			$this->column_guids = json_encode($column_guids);
			return $this->save();
		}
	}
	
	/**
	 * When deleting, delete columns too!
	 */
	public function delete(){
		foreach($this->getColumns() as $column){
			if($column)
				$column->delete();
		}
		
		parent::delete();
	}
	
	/**
	 * Returns settings
	 */
	public function getSettings(){
		return $this;
	}
}
