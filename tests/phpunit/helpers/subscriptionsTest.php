<?php

use Minds\Helpers;

class subscriptionsTest extends \Minds_PHPUnit_Framework_TestCase {

	
        private function setupUser($space =1){
            $user = new minds\entities\user();
            $user->username = "user$space";
            $user->email = "user$space@minds.com";
            $user->name = "Test user";
            return $user->save();
        }
    
        public function testSubscribe(){
            $user1 = $this->setupUser(1);
            $user2 = $this->setupUser(2);
            
            $this->assertTrue( Helpers\Subscriptions::subscribe($user1, $user2) );
            $this->assertTrue( Helpers\Subscriptions::isSubscribed($user1, $user2));
            $this->assertTrue( Helpers\Subscriptions::isSubscriber($user2, $user1));
        }
        
        public function testUnSubscribe(){
            $user3 = $this->setupUser(3);
            $user4 = $this->setupUser(4);
            
            //quick subscribe test first, even though we just tested
            $this->assertTrue( Helpers\Subscriptions::subscribe($user3, $user4) );
            
            $this->assertTrue( Helpers\Subscriptions::unSubscribe($user3, $user4) );
            $this->assertFalse( Helpers\Subscriptions::isSubscribed($user3, $user4));
            $this->assertFalse( Helpers\Subscriptions::isSubscriber($user4, $user3));
        }
	
}