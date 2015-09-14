<?php

use Minds\Core\Data;
use Minds\Core\Boost;
use Minds\tests\phpunit\mocks;

class ChannelBoostTest extends \Minds_PHPUnit_Framework_TestCase {

    /**
     * Run before each test
     */
    protected function setUp() {
        $db = mocks\MockCassandra::build('user_index_to_guid');
        $db->preload(array(
          'p2p-001' => array("p2p-001"=>time()),
          'p2p-0011' => array("p2p-0011"=>time())
        ));

        $this->setUser();

        //setup lookup router
        $lookup = new Data\lookup();
        $lookup->set('channel_name', array("001"=>"001"));
    }

    public function testCanLoadFromFactory() {
        $channel = Boost\Factory::build('Channel');
        $this->assertInstanceOf('\Minds\Core\Boost\Channel', $channel);
        $this->assertInstanceOf('\Minds\interfaces\BoostHandlerInterface', $channel);
    }

    public function testCanRequestBoost(){
        $channel = Boost\Factory::build('Channel', array('destination'=>'channel_name'));
        $result = $channel->boost("002", 10);
        $this->assertEquals("boost:channel:001:review", $result);
    }

    public function testCanAcceptBoost(){

        $tmp_entity = new Minds\entities\object();
        $tmp_entity->save();

        Boost\Factory::build('Channel', array('destination'=>'p2p-001'))->boost($tmp_entity->guid, 10);
        $ctrl = Boost\Factory::build('Channel', array('destination'=>'p2p-001'));
        $guids = $ctrl->getReviewQueue(1);

        $this->assertEquals(1, count($guids));
        $this->assertTrue($ctrl->accept($tmp_entity->guid,10));
        $this->assertFalse(Boost\Factory::build('Channel', array('destination'=>'p2p-001'))->getReviewQueue(1));
    }

    public function testCanRejectBoost(){
        Boost\Factory::build('Channel', array('destination'=>'p2p-0011'))->boost("p2p-entity-003", 10);
        $this->assertTrue(Boost\Factory::build('Channel', array('destination'=>'p2p-0011'))->reject("p2p-entity-003"));
        $this->assertFalse(Boost\Factory::build('Channel', array('destination'=>'p2p-0011'))->getReviewQueue(1));
    }

}
