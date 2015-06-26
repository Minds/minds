<?php

use Minds\Core\Data;

class MongoDBTest extends \Minds_PHPUnit_Framework_TestCase {

        private static $client;

		/**
		 * Set up the new column family
		 * 
		 * @return void
		 */
        public static function setUpBeforeClass() {
			self::$client = Data\Client::build('MongoDB');
        }
		
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
       	public function testInsert(){
			//$this->assertEquals();
       	}

}