<?php

class blogTest extends Minds_PHPUnit_Framework_TestCase {
	
		/**
		 * Set up the new column family
		 * 
		 * @return void
		 */
        public static function setUpBeforeClass() {
        	//plugin classes don't autoload
			require_once(dirname(dirname(__FILE__)) . '/classes/ElggBlog.php'); 
        }

        protected function setUp() {
			$this->setUser();
        }

        public function testCanConstructWithoutArguments() {
                $this->assertNotNull(new ElggBlog());
        }
		
		public function testSave(){
			$object = new ElggBlog();
			$object->title = "object test";
			$object->description = "";
			$this->assertInternalType('string', $object->save());
		}
		
		public function testLoadBlog(){
			$object = new ElggBlog();
			$object->title = "object test";
			$object->description = "";
			$guid = $object->save();
			invalidate_cache_for_entity($guid);
			
			$blog = new ElggBlog($guid);
			$this->assertEquals('object test', $blog->title);
			$this->assertInternalType('array',$blog->ownerObj);
			$this->assertInstanceOf('ElggUser', $blog->getOwnerEntity());
		}

}