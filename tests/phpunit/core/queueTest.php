<?php

use Minds\Core;

class queueTest extends \Minds_PHPUnit_Framework_TestCase {
		

		
		/**
		 * Run before each test
		 * 
		 * @return void
		 */
		protected function setUp() {
		}
	
		/**
		 * Test inserting into the database
		 * 
		 * @return void
		 */
       	public function testFactory(){
			$this->assertInstanceOf('Minds\Core\Queue\Interfaces\QueueClient', Core\Queue\Client::build("RabbitMQ"));
       	}
        
        public function testSend(){
            Core\Queue\Client::build("RabbitMQ")
                ->setExchange()
                ->setQueue()
                ->send("Testing send");
        }
        
        public function testReceive(){
            $rq = Core\Queue\Client::build("RabbitMQ")
                ->setExchange()
                ->setQueue("newqueue")
                ->send("Testing send");
                
             sleep(1);   
             
             $rq->receive(function($msg) use ($rq){
                $this->assertEquals("Testing send", $msg->getData());
                $rq->close();
             });
             
        }
	
}