<?php
/**
 * Minds Navigation Factory
 */
namespace Minds\Core\Navigation;

class Factory{

	private array $items = array();

	static public function build(){

	}

	static public function add($item){
		$items[] = $item;
	}

}
