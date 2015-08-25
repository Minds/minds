<?php
/**
 * Minds Navigation Manager
 */
namespace Minds\Core\Navigation;

class Manager{

	static private $items = array();

	static private function defaults(){
		$newsfeed = new Item();
		$newsfeed->setPriority(1)
			->setIcon('home')
			->setName('Newsfeed')
			->setTitle('Newsfeed')
			->setPath('/newsfeed');
		self::add($newsfeed);

		$capture = new Item();
		$capture->setPriority(2)
			->setIcon('videocam')
			->setName('Capture')
			->setTitle('Capture')
			->setPath('/capture');
		self::add($capture);

		$discovery_suggested = new Item();
		$discovery_suggested
			->setPriority(1)
			->setIcon('call_split')
			->setName('Suggested')
			->setTitle('Suggested (Discovery)')
			->setPath('/discovery')
			->setParams(array(
				'filter' => 'suggested',
				'type' => ''
			));

		$discovery_trending = new Item();
		$discovery_trending
			->setPriority(2)
			->setIcon('trending_up')
			->setName('Trending')
			->setTitle('Trending (Discovery)')
			->setPath('/discovery')
			->setParams(array(
				'filter' => 'trending',
				'type' => ''
			));

		$discovery_featured = new Item();
		$discovery_featured
			->setPriority(3)
			->setIcon('star')
			->setName('Featured')
			->setTitle('Featured (Discovery)')
			->setPath('/discovery')
			->setParams(array(
				'filter' => 'featured',
				'type' => ''
			));

		$discovery = new Item();
		$discovery->setPriority(3)
			->setIcon('search')
			->setName('Discovery')
			->setTitle('Discovery')
			->setPath('/discovery')
			->setParams(array(
				'filter' => 'featured',
				'type' => ''
			))
			->addSubItem($discovery_suggested)
			->addSubItem($discovery_trending)
			->addSubItem($discovery_featured);
		self::add($discovery);
	}

	/**
	 * Add an item to the Navigation
	 * @return void
	 */
	static public function add($item){
		if($item instanceof Item)
			self::$items[] = $item;
	}

	/**
	 * Return items
	 * @return array
	 */
	static public function export(){
		self::defaults();
		$items = array();

		usort(self::$items, function($a, $b){
			if($a->getPriority() > $b->getPriority())
				return 1;
			return -1;
		});

		foreach(self::$items as $item){
			$items[] = $item->export();
		}
		return $items;
	}

}
