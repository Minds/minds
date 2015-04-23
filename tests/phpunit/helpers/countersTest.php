<?php

use Minds\Helpers;

class countersTest extends \Minds_PHPUnit_Framework_TestCase {

	
        public function testIncrement(){
            Helpers\Counters::increment(1, "test");
            $this->assertEquals(1, Helpers\Counters::get(1, "test", false));
        }
        
        public function testBatch(){
            Helpers\Counters::incrementBatch(array(1,2,3), "testbatch");
            sleep(1);
            $this->assertEquals(1, Helpers\Counters::get(1, "testbatch", false));
            $this->assertEquals(1, Helpers\Counters::get(2, "testbatch", false));
            $this->assertEquals(1, Helpers\Counters::get(3, "testbatch", false));
        }
        
        public function testClear(){
            Helpers\Counters::increment(1, "testclear", 10);
            Helpers\Counters::clear(1, "testclear");
            $this->assertEquals(0,  Helpers\Counters::get(0, "testclear", false));
        }
	
}