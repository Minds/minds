<?php

use Minds\Core\Data;
use Minds\tests\phpunit\mocks;

class dataTest extends \Minds_PHPUnit_Framework_TestCase {

		private $db;

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
			$this->db = new Data\Call('test');
			$mock = mocks\MockCassandra::build('test');
      $mock->preload(array(
				'test_get' => array('foo' => 'bar'),
				'test_multiget_1' => array('foo' => 'bar'),
				'test_multiget_2' => array('foo' => 'bar'),
				'test_remove_attributes' => array('foo' => 'bar', 'bar' => 'foo'),
				'test_remove' => array('foo' => 'bar')
			));
		}

		/**
		 * Test inserting into the database
		 *
		 * @return void
		 */
    public function testInsert(){
			$this->assertEquals('test_insert', $this->db->insert('test_insert', array('foo'=>'bar')));
    }

		/**
		 * Test retrieving a row from the database
		 *
		 * @return void
		 */
		public function testGetRow(){
			$this->assertEquals(array('foo'=>'bar'), $this->db->getRow('test_get'));
		}

		/**
		 * Test retrieving multiple rows from the database
		 *
		 * @return void
		 */
		public function testGetRows(){
			$this->assertCount(2, $this->db->getRows(array('test_multiget_1', 'test_multiget_2')));
			$this->assertArrayHasKey('test_multiget_1', $this->db->getRows(array('test_multiget_1')));
		}

		/**
		 * Test removing an attribute (column) from a row
		 *
		 * @return void
		 */
		public function testRemoveAttributes(){
			$this->db->removeAttributes('test_remove_attributes', array('bar'));
			$row = $this->db->getRow('test_remove_attributes');
			$this->assertFALSE(isset($row['bar']));
			$this->assertTrue(isset($row['foo']));
		}

		/**
		 * Test removing a row
		 *
		 * @return void
		 */
		public function testRemoveRow(){
			$this->db->removeRow('test_remove');
			//we can't find, then its good!
			$this->assertFalse($this->db->getRow('test_remove'));
		}

}
