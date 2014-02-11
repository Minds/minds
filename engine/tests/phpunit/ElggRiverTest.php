<?php

class ElggRiverTest extends Minds_PHPUnit_Framework_TestCase {

	static $subject_guid;
	static $object_guid;

        protected function setUp() {
			self::$subject_guid = register_user('test', 'testpsw', 'Test', 'test@test.com');
		
			$blog = new ElggObject();
			$blog->title = 'Test blog';
			$blog->description = 'this is the description';
			self::$object_guid = $blog->save();
        }
		
		public function testAddtoRiver(){
			
			$id = add_to_river('river/test', 'create', self::$subject_guid, self::$object_guid);
			$this->assertNotNull($id);
	
		}
		
		public function testRemoveItem(){
			$id = add_to_river('river/test', 'create', self::$subject_guid, self::$object_guid);
			elgg_delete_river(array(
				'id'=>$id
			));
		}
		
		public function testGetRiver(){
			/*$userline = elgg_get_river(array(
				'type'=> ''));*/
		}
		
		public function tearDown(){
			$user = get_entity(self::$subject_guid);
			$user->delete();
		}

}