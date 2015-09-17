<?php

use Minds\Core;

class routerTest extends \Minds_PHPUnit_Framework_TestCase {

		private $router;
		
		/**
		 * Run before each test
		 * 
		 * @return void
		 */
		protected function setUp() {
			$this->router = new core\router();
		}
		
		private function register(){
			return core\Router::registerRoutes(array(
       			'/test' => "minds\\pages\\test",
			));
		}
	
		/**
		 * Test registering a root
		 * 
		 * @return void
		 */
       	public function testRegister(){
       		$this->assertArrayHasKey('/test',$this->register());
       	}
		
		/**
		 * Test loading GET
		 * 
		 * @return void
		 */
		public function testMethods(){
			$this->register();
			
			foreach(array('get', 'post', 'put', 'delete') as $method){
				ob_start();
					$this->router->route('/test', $method);
					$output = ob_get_contents();
				ob_end_clean();
				
				$this->assertEquals('this is a '.$method.' request', $output);
			}
			
		}
		
		/**
		 * Test page slugs
		 */
		public function testSlugs(){
			$this->register();
			
			ob_start();
				$this->router->route('/test/this/is/a/page', 'GET');
				$output = ob_get_contents();
			ob_end_clean();
			
			$this->assertEquals('you have 4 pages', $output);
		}

		/**
		 * Tear down
		 * 
		 * @return void
		 */
		static public function tearDownAfterClass(){
		}

}