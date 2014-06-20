<?php

class ElggObjectTest extends Minds_PHPUnit_Framework_TestCase {

        protected function setUp() {
                // required by ElggEntity when setting the owner/container
                //_elgg_services()->setValue('session', new ElggSession(new Elgg_Http_MockSessionStorage()));
        }

        public function testCanConstructWithoutArguments() {
                $this->assertNotNull(new ElggObject());
        }
		
		public function testSave(){
			$object = new ElggObject();
			$object->title = "object test";
			$object->description = "";
			$this->assertInternalType('string', $object->save());
		}

		public function testOwnerEntity(){
			
		}
}