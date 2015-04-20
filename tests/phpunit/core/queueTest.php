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
	

}