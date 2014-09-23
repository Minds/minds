<?php

class entityTest extends Minds_PHPUnit_Framework_TestCase {
		
        protected function setUp() {
                $this->setUser();
        }

        public function testCanConstructWithoutArguments() {
                $this->assertNotNull(new minds\entities\entity());
        }
		
		private function setupEntity($space =1){
			$entity = new minds\entities\entity();
			$entity->title = "entity $space";
			$entity->description = "description $space";
			return $entity->save();
		}
		
		public function testSave(){
			$entity = new minds\entities\entity();
			$entity->title = "object test";
			$entity->description = "";
			$this->assertInternalType('string', $entity->save());
		}
		
		public function testLoadFromGuid(){
			$guid = $this->setupEntity(2);
			invalidate_cache_for_entity($guid);
			
			$entity = new minds\entities\entity($guid);
			$this->assertEquals('entity 2', $entity->title);
			//$this->assertInternalType('array',$entity->ownerObj);
			$this->assertInstanceOf('minds\entities\user', $entity->getOwnerEntity());
		}
}
