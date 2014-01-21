<?php

class ElggEntitiesTest extends Minds_PHPUnit_Framework_TestCase {

	static $entities = array();
	/**
	 * Run before each test
	 *
	 * @return void
	 */
	protected function setUp() {
		/**
		 * Create a bunch of test entities (10)
		 */
		for ($i = 1; $i <= 10; $i++) {
			$entity = new ElggObject();
			$entity -> title = "Entity $i";
			$entity -> subtype = 'subtype';
			$entity -> save();
			self::$entities[] = $entity;
		}
	}

	protected function tearDown() {
		/**
		 * Delete the entities we created
		 */
		foreach (self::$entities as $entity) {
			//$entity->delete();
		}
	}

	/**
	 * Test the elgg_get_entities(); function
	 */
	public function testElggGetEntity() {
		$objects = elgg_get_entities(array('type' => 'object', 'limit' => 10));
		$this -> assertCount(10, $objects);

		//test with subtypes
		$subtypes = elgg_get_entities(array('type' => 'object', 'subtype' => 'subtype', 'limit' => 10));
		$this -> assertCount(10, $subtypes);

		//test none
		$none = elgg_get_entities(array('type' => 'object', 'subtype' => 'doesnotexist', 'limit' => 10));
		$this -> assertNULL($none);
	}

	/**
	 * Test featuring entities
	 */
	public function testFeature(){
		$entity = self::$entities[0];
		$featured_id = $entity->feature();
		
		$db = new DatabaseCall('entities_by_time');
		$guids = $db->getRow('object:featured', array('limit'=>1));
		$this->assertArrayHasKey($featured_id, $guids);
	}
	
	/**
	 * Test unfeaturing entities
	 */
	public function testUnFeature(){
		
		foreach(self::$entities as $k => $entity){
			if($k<2){ //feature 3 entities
				$featured_ids[$k] = $entity->feature();
			}
		}
		
		self::$entities[0]->unfeature();
		
		$db = new DatabaseCall('entities_by_time');
		$guids = $db->getRow('object:featured', array('limit'=>10));
		$this->assertCount(2, $guids);
		$this->assertEquals(0, self::$entities[0]->featured);
	}
}
