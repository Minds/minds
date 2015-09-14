<?php

use Minds\Helpers;
use Minds\Core;

class countersTest extends \Minds_PHPUnit_Framework_TestCase {

    private function mock(){
        return $mock = $this->getMockBuilder('\Minds\Core\Data\Cassandra\Client')
          ->disableOriginalConstructor()
          ->getMock();
    }

    public function testIncrement(){
      $client = $this->mock();

      $query = $query = new Core\Data\Cassandra\Prepared\Counters();

      $client->expects($this->once())
          ->method('request')
          ->with($query->update(1, "test", 1));

        Helpers\Counters::increment(1, "test", 1, $client);
      //  $this->assertEquals(1, Helpers\Counters::get(1, "test", false));
    }

    public function testBatch(){
      return;
        Helpers\Counters::incrementBatch(array(1,2,3), "testbatch");
        sleep(1);
        $this->assertEquals(1, Helpers\Counters::get(1, "testbatch", false));
        $this->assertEquals(1, Helpers\Counters::get(2, "testbatch", false));
        $this->assertEquals(1, Helpers\Counters::get(3, "testbatch", false));
    }

    public function testClear(){
      $client = $this->mock();

      $query = $query = new Core\Data\Cassandra\Prepared\Counters();

      $client->expects($this->exactly(2))
          ->method('request')
          ->with($this->logicalOr(
            $this->equalTo($query->get(1, "testclear")),
            $this->equalTo($query->update(1, "testclear", 0))
            ));


        Helpers\Counters::clear(1, "testclear", NULL, $client);
    }

}
