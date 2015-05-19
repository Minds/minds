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
        $this->assertEquals("boost:newsfeed:review", $result);
        
        $db = new Data\Call('entities_by_time'); 
        $confirm = $db->getRow("boost:newsfeed:review");
        $this->assertEquals("1000", key($confirm));
        $this->assertEquals(10, $confirm["1000"]);
    }
    
    public function testCanAcceptBoost(){
       Boost\Factory::build('Newsfeed')->boost("2000", 10);
       $this->assertEquals("boost:newsfeed", Boost\Factory::build('Newsfeed')->accept("2000", 10));
    }
    
    public function testCanRejectBoost(){
        Boost\Factory::build('Newsfeed')->boost("3000", 10);
        Boost\Factory::build('Newsfeed')->reject("3000");
        $db = new Data\Call('entities_by_time'); 
        $this->assertArrayNotHasKey("3000",  $db->getRow("boost:newsfeed:review"));
    }

}
