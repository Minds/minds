<?php

class GroupsTest extends Minds_PHPUnit_Framework_TestCase {
	
		static $group_obj;
		static $user_obj;
	
		/**
		 * Set up the new column family
		 * 
		 * @return void
		 */
        public static function setUpBeforeClass() {
			//require_once(dirname(dirname(__FILE__)) . '/start.php'); 
        }

        protected function setUp() {
			//create a new elgg group
			$group = new ElggGroup();
			$group->name = 'A test group';
			$group->access_id = ACCESS_LOGGED_IN;
			$group->save();
			self::$group_obj = $group;
			
			self::$user_obj = new ElggUser(register_user('testuser', 'test123', 'Test User', 'test@minds.com'));

			$_SESSION['guid'] = self::$user_obj->getGUID();
			$_SESSION['user'] = self::$user_obj;
			$_SESSION['username'] = 'testuser';
        }
		
		protected function tearDown(){
			self::$user_obj->delete();
		}

        public function testCanConstructWithoutArguments() {
                $this->assertNotNull(new ElggGroup());
        }
		
		public function testJoin(){
			$this->assertTrue(self::$group_obj->join(self::$user_obj));
			$this->assertTrue(self::$group_obj->isMember(self::$user_obj));
		}
		
		public function testLeave(){
			//we need to join first
			$this->assertTrue(self::$group_obj->join(self::$user_obj));
			//then we need to leave
			$this->assertTrue(self::$group_obj->leave(self::$user_obj));
			//check the user has left the group
			$this->assertFalse(self::$group_obj->isMember(self::$user_obj));
		}

}