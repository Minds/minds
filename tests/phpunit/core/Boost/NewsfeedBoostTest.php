<?php

use Minds\Core\Data;
use Minds\Core\Boost;

class NewsfeedBoostTest extends \Minds_PHPUnit_Framework_TestCase {
    
    /**
     * Run before each test
     */
    protected function setUp() {
        //$this->setUser();
    }

    public function testCanLoadFromFactory() {
        $newsfeed = Boost\Factory::build('Newsfeed');
        $this->assertInstanceOf('\Minds\Core\Boost\Newsfeed', $newsfeed);
        $this->assertInstanceOf('\Minds\interfaces\BoostHandlerInterface', $newsfeed);
    }

    public function testCanRequestBoost(){
        $result = Boost\Factory::build('Newsfeed')->boost("1000", 10);
        $this->assertEquals($result['err'], NULL);
    }
    
    public function testCanAcceptBoost(){
       Boost\Factory::build('Newsfeed')->boost("2000", 10);
       
       $queue = Boost\Factory::build('Newsfeed')->getReviewQueue(1);
       foreach($queue as $boost){
           $result = Boost\Factory::build('Newsfeed')->accept((string) $boost['_id']);

       }
       $this->assertEquals(0, Boost\Factory::build('Newsfeed')->getReviewQueueCount());
    }
    
    public function testCanRejectBoost(){
        Boost\Factory::build('Newsfeed')->boost("2000", 10);
       
        $queue = Boost\Factory::build('Newsfeed')->getReviewQueue(1);
        foreach($queue as $boost){
            $result = Boost\Factory::build('Newsfeed')->reject((string) $boost['_id']);
        }
        $this->assertEquals(0, Boost\Factory::build('Newsfeed')->getReviewQueueCount());
    }

    public function testCanExpireBoost(){
        Boost\Factory::build('Newsfeed')->boost("2000", 10);
        for($i=1; $i<10; $i++){
            Boost\Factory::build('Newsfeed')->getBoost();
        }
      //  Boost\Factory::build('Newsfeed')->
    }

}
