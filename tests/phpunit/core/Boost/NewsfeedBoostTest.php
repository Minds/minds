<?php

use Minds\Core\Data;
use Minds\Core\Boost;

class NewsfeedBoostTest extends Minds_PHPUnit_Framework_TestCase {

    /**
     * Run before each test
     */
    protected function setUp() {
        //$this->setUser();
    }

    protected function mock(){
      var_dump($this); exit;
      return $this->getMock('\Minds\Core\Data\MongoDB\Client')
        ->disableOriginalConstructor();
    }

    public function testCanLoadFromFactory() {
        $newsfeed = Boost\Factory::build('Newsfeed', array(), $this->mock());
        $this->assertInstanceOf('\Minds\Core\Boost\Newsfeed', $newsfeed);
        $this->assertInstanceOf('\Minds\Interfaces\BoostHandlerInterface', $newsfeed);
    }

    public function testCanRequestBoost(){
        $db = $this->mock();
        $db->expects($this->once())
            ->method('insert')
            ->will($this->returnValue(array('err'=>NULL)));

        $result = Boost\Factory::build('Newsfeed', array(), $db)->boost("1000", 10);
        $this->assertEquals($result['err'], NULL);
    }

    public function testCanAcceptBoost(){

        $collection_mock = new \Minds\tests\phpunit\mocks\MockMongoCursor(array(array('_id'=>'abc123', 'guid'=>'abc123', 'impressions'=>10)));

        $db = $this->mock();
        $db->expects($this->once())
            ->method('update')
            ->with("boost", array('_id' => "abc123"), array('state'=>'approved'))
            ->will($this->returnValue(array('err'=>NULL)));

        $db->expects($this->once())
            ->method('find')
            ->will($this->returnValue($collection_mock));

        $result = Boost\Factory::build('Newsfeed', array(), $db)->accept("abc123");
        $this->assertEquals($result['err'], NULL);
    }

   public function testCanRejectBoost(){
        $collection_mock = new \Minds\tests\phpunit\mocks\MockMongoCursor(array(array('_id'=>'abc123', 'guid'=>'abc123', 'impressions'=>10)));

        $db = $this->mock();
        $db->expects($this->once())
            ->method('remove')
            ->with("boost", array('_id' => "abc123"))
            ->will($this->returnValue(array('err'=>NULL)));

        $db->expects($this->once())
            ->method('find')
            ->will($this->returnValue($collection_mock));

        $result = Boost\Factory::build('Newsfeed', array(), $db)->reject("abc123");
        $this->assertEquals($result['err'], NULL);
    }

    public function testCanExpireBoost(){
        $collection_mock = new \Minds\tests\phpunit\mocks\MockMongoCursor(array(array('_id'=>'abc123', 'guid'=>'abc123', 'impressions'=>-1)));

        $db = $this->mock();
        $db->expects($this->once())
            ->method('find')
            ->will($this->returnValue($collection_mock));

        $db->expects($this->once())
            ->method('remove')
            ->with("boost", array('_id' => "abc123"))
            ->will($this->returnValue(array('err'=>NULL)));

        $result = Boost\Factory::build('Newsfeed', array(), $db)->getBoost();
    }

}
