<?php

class ElggRelationshipsTest extends Minds_PHPUnit_Framework_TestCase {

		static $user_a_obj;
		static $user_b_obj;
		protected static $password = 'password';
		
        protected function setUp() {
               /**
			    * Create two users
			    */
			   self::$user_a_obj = new ElggUser(register_user('test', self::$password,'test a', 'test@minds.com'));
			   self::$user_b_obj = new ElggUser(register_user('test2', self::$password,'test b', 'test2@minds.com'));
        }
		
		protected function tearDown(){
			self::$user_a_obj->delete();
			self::$user_b_obj->delete();
		}

		private function createRelationship(){
			if( add_entity_relationship(self::$user_a_obj->getGUID(), 'buddy', self::$user_b_obj->getGUID())
				&&
				add_entity_relationship(self::$user_b_obj->getGUID(), 'buddy', self::$user_a_obj->getGUID()))
				{
					return true;
				}
		}

		public function testCreateRelationship(){
			$this->assertTrue($this->createRelationship());
		}
		
		public function testGetEntitiesFromRelationship(){
			$this->createRelationship();
			$users = elgg_get_entities_from_relationship(array('type'=>'user','relationship_guid'=>self::$user_a_obj->getGUID(), 'relationship'=>'buddy'));
			$this->assertCount(1, $users);
			$this->assertEquals(self::$user_b_obj->getGUID(), $users[0]->getGUID());
		}
		
		public function testGetEntitiesFromRelationshipReverse(){
			$this->createRelationship();
			$users = elgg_get_entities_from_relationship(array('type'=>'user','relationship_guid'=>self::$user_a_obj->getGUID(), 'relationship'=>'buddy', 'inverse_relationship'=>true));
			$this->assertCount(1, $users);
			$this->assertEquals(self::$user_b_obj->getGUID(), $users[0]->getGUID());
		}
		
		public function testRemoveRelationship(){
			$this->createRelationship();
			
			$this->assertTrue(remove_entity_relationship(self::$user_a_obj->getGUID(), 'buddy', self::$user_b_obj->getGUID()));
			$user_a_buddies = elgg_get_entities_from_relationship(array('type'=>'user','relationship_guid'=>self::$user_a_obj->getGUID(), 'relationship'=>'buddy'));
			$this->assertFalse($user_a_buddies);
			$user_b_buddiesof = elgg_get_entities_from_relationship(array('type'=>'user','relationship_guid'=>self::$user_b_obj->getGUID(), 'relationship'=>'buddy', 'inverse_relationship'=>true));
			$this->assertFalse($user_b_buddiesof);
			
			$this->assertTrue(remove_entity_relationships(self::$user_b_obj->getGUID(), 'buddy'));
			//user b should now have no buddies
			$user_b_buddies = elgg_get_entities_from_relationship(array('type'=>'user','relationship_guid'=>self::$user_b_obj->getGUID(), 'relationship'=>'buddy'));
			$this->assertFalse($user_b_buddies);
		}

}