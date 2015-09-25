<?php
/**
 * Minds Navigation Manager
 */
namespace Minds\Core\Navigation;

class Manager{

	static private $containers = array();

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
	 * @param Item $item - the item to add to the navigation
	 * @param string $container - the container to add the item to
	 * @return void
	 */
	static public function add($item, $container = "sidebar"){
		if($item instanceof Item)
			self::getContainer($container)->add($item);
	}

	/**
	 * Indepotent get or create container
	 * @param string $container - the name or ID of the container
	 * @return Container
	 */
	static private function getContainer($container){
		if(!isset(self::$containers[$container]))
			self::$containers[$container] = new Container();
		return self::$containers[$container];
	}

	/**
	 * Return items
	 * @param string $container - the container to export
	 * @return array
	 */
	static public function export($container = NULL){
		self::defaults();
		$containers = array();

		foreach(self::$containers as $id => $container){
			$containers[$id] = $container->export();
		}
		return $containers;
	}

}
