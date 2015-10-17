<?php
/**
 * Minds Navigation Container
 */
namespace Minds\Core\Navigation;

class Container {

	private $items = array();

	/**
	 * Add an item to the Navigation
	 * @return void
	 */
	public function add($item){
		if($item instanceof Item)
			$this->items[] = $item;
	}

	/**
	 * Return items
	 * @return array
	 */
	public function export(){

		$items = array();

		@usort($this->items, function($a, $b){
			if($a->getPriority() > $b->getPriority())
				return 1;
			return -1;
		});

		foreach($this->items as $item){
			$items[] = $item->export();
		}
		return $items;
	}

}
