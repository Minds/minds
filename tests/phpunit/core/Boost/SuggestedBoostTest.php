<?php

use Minds\Core\Data;
use Minds\Core\Boost;

class SuggestedBoostTest extends \Minds_PHPUnit_Framework_TestCase {

    /**
     * Run before each test
     */
    protected function setUp() {
        //$this->setUser();
    }

    protected function mock(){
      return $mock = $this->getMockBuilder('\Minds\Core\Data\MongoDB\Client')
        ->disableOriginalConstructor()
        ->getMock();
    }

    public function testCanLoadFromFactory() {
        $suggested = Boost\Factory::build('Suggested', array(), $this->mock());
        $this->assertInstanceOf('\Minds\Core\Boost\Suggested', $suggested);
        $this->assertInstanceOf('\Minds\Interfaces\BoostHandlerInterface', $suggested);
    }

    public function testCanRequestBoost(){
        $db = $this->mock();
        $db->expects($this->once())
            ->method('insert')
            ->will($this->returnValue(array('err'=>NULL)));

        $result = Boost\Factory::build('Suggested', array(), $db)->boost("1000", 10);
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

        $result = Boost\Factory::build('Suggested', array(), $db)->accept("abc123");
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

        $result = Boost\Factory::build('Suggested', array(), $db)->reject("abc123");
        $this->assertEquals($result['err'], NULL);
    }

    /*public function testCanExpireBoost(){
        $collection_mock = new \Minds\tests\phpunit\mocks\MockMongoCursor(array(array('_id'=>'abc123', 'guid'=>'abc123', 'impressions'=>-1)));

        $db = $this->mock();
        $db->expects($this->once())
            ->method('find')
            ->will($this->returnValue($collection_mock));

        $db->expects($this->once())
            ->method('remove')
            ->with("boost", array('_id' => "abc123"))
            ->will($this->returnValue(array('err'=>NULL)));

        $result = Boost\Factory::build('Suggested', array(), $db)->getBoost();
    }*/

}
