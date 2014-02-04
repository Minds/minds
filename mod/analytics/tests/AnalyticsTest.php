<?php

class AnalyticsTest extends Minds_PHPUnit_Framework_TestCase {

	static $entities = array();
	
	static public function setUpBeforeClass(){
		require_once(dirname(dirname(__FILE__)) . '/start.php');
	}
	
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
			$entity->title = "Entity $i";
			$entity->subtype = 'subtype';
			self::$entities[] = $entity->save();
		}
		
		$data = array();
		foreach(self::$entities as $entity_guid){
			array_push($data, $entity_guid);
		}

	}

	protected function tearDown() {
		
	}
	
	public function testGetTrending(){
		/*$entities = analytics_retrieve();
		$this->assertCount(10, $entities);*/
	}

}
