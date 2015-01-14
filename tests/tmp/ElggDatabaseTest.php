<?php

class ElggDatabaseTest extends Minds_PHPUnit_Framework_TestCase {
		
		static $db = null;
		static $key = 'test_key';
		static $values = array('k1'=>'v1', 'k2'=>'v2', 'index'=>'index');

		/**
		 * Set up the new column family
		 * 
		 * @return void
		 */
        public static function setUpBeforeClass() {
			self::$db = new Minds\Core\Data\Call();
			self::$db->createCF('test', array('index'=> 'UTF8Type'));
			self::$db = new Minds\Core\Data\Call('test'); 
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
			$this->assertEquals(self::$key, self::$db->insert(self::$key, self::$values));
       	}
		
		/**
		 * Test retrieving a row from the database
		 * 
		 * @return void
		 */
		public function testGetRow(){
			$this->assertEquals(self::$values, self::$db->getRow(self::$key));
		}
		
		/**
		 * Test retrieving multiple rows from the database
		 * 
		 * @return void
		 */
		public function testGetRows(){
			$this->assertCount(1, self::$db->getRows(array(self::$key)));
			$this->assertArrayHasKey(self::$key, self::$db->getRows(array(self::$key)));
		}
		
		/**
		 * Test querying
		 * 
		 * @return void
		 */
		public function testGetByIndex(){
			$this->assertCount(1, self::$db->getByIndex(array('index'=>'index')));
		}
			
		/**
		 * Test removing an attribute (column) from a row
		 * 
		 * @return void
		 */	
		public function testRemoveAttributes(){
			self::$db->removeAttributes(self::$key, array('k1'));//delete k1
			$row = self::$db->getRow(self::$key);
			$this->assertFALSE(isset($row['k1']));
		}
		
		/**
		 * Test removing a row
		 * 
		 * @return void
		 */
		public function testRemoveRow(){
			self::$db->removeRow(self::$key);//delete
			//we can't find, then its good!
			$this->assertFalse(self::$db->getRow(self::$key));
		}

		/**
		 * Delete the new column family
		 * 
		 * @return void
		 */
		static public function tearDownAfterClass(){
			self::$db->removeCF();
		}

}