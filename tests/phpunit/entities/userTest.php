<?php

class userTest extends Minds_PHPUnit_Framework_TestCase {
		
        protected function setUp() {
        }
		
        public function testCanConstructWithoutArguments() {
                $this->assertNotNull(new minds\entities\object());
        }
		
		private function setupUser($space =1){
			$user = new minds\entities\user();
			$user->username = "user$space";
			$user->email = "user$space@minds.com";
			$user->name = "Test user";
			return $user->save();
		}
			
		public function testSave(){
			
			$this->assertInternalType('string', $this->setupUser(1));
		}
		
		public function testLoadFromGuid(){
			$guid = $this->setupUser(2);
			invalidate_cache_for_entity($guid);
			
			$user = new minds\entities\user($guid);
			$this->assertEquals('user2', $user->username);
		}
		
		public function testLoadFromUsername(){
			$guid = $this->setupUser(3);
			invalidate_cache_for_entity($guid);
			
			$user = new minds\entities\user('user3');
			$this->assertEquals('user3@minds.com', $user->email);
			$this->assertNotNull($user->guid);
		}

        public function testEmail(){
            $user = new minds\entities\user();
            $user->setEmail("mail@minds.com");
            $this->assertNotNull($user->email);
            $this->assertNotEquals("mail@minds.com", $user->email);
            $this->assertEquals("mail@minds.com", $user->getEmail());
        }

}