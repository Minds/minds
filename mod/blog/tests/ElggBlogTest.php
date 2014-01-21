<?php

class ElggBlogTest extends Minds_PHPUnit_Framework_TestCase {
	
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
                // required by ElggEntity when setting the owner/container
                //_elgg_services()->setValue('session', new ElggSession(new Elgg_Http_MockSessionStorage()));
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

}