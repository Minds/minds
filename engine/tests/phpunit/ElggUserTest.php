<?php

class ElggUserTest extends Minds_PHPUnit_Framework_TestCase {

		static $user;
		protected static $password = 'password';
		
        protected function setUp() {
               /**
			    * Create a user, just for tests sake
			    */
			   self::$user = new ElggUser(register_user('test', self::$password,'test', 'test@minds.com'));
        }
		
		protected function tearDown(){
			self::$user->delete();
		}

        public function testCanConstructWithoutArguments() {
               // $this->assertNotNull(new ElggUser());
        }
		
		public function testCanLoadFromConstructWithGUID(){
			$test = new ElggUser(self::$user->guid);
			$this->assertEquals(self::$user->name, $test->name);
		}
		
		public function testSave(){
			self::$user->name = 'My New Name';
			$this->assertInternalType('string', self::$user->save());
		}
		
		public function testAuthenticate(){
			register_pam_handler('pam_auth_userpass');
			$this->assertTrue(elgg_authenticate(self::$user->username, self::$password));
			$user_obj = get_user_by_username(self::$user->username);
			
			$this->assertEquals($user_obj->guid, self::$user->guid);
		}

}