<?php

use Minds\Core\Data;
use Minds\tests\phpunit\mocks;

class RelationshipsTest extends \Minds_PHPUnit_Framework_TestCase {

    private $client;

		/**
		 * Set up the new column family
		 *
		 * @return void
		 */
    public static function setUpBeforeClass() {

    }

		/**
		 * Run before each test
		 *
		 * @return void
		 */
		protected function setUp() {
      $mock = mocks\MockCassandra::build('relationships');
      $mock->preload(array(
        "guid1:test" => array("guid2" => time()),
        "guid1:test:inverted" => array("guid2" => time())
      ));

      $this->client = new Data\Relationships();
		}

    /**
     * Test creating a relationship
     */
     public function testCreate(){
       $this->assertTrue($this->client->create("guid1", "test", "guid2"));
     }

     public function testCheck(){
       $this->assertTrue($this->client->check("guid1", "test", "guid2"));
     }

     public function testCheckInverse(){
       $this->assertTrue($this->client->check("guid2", "test", "guid1"));
     }

}
