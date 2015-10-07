<?php

class Minds_PHPUnit_Framework_TestCase extends PHPUnit_Framework_TestCase {

		static $KS = 'phpunit_minds_test_cases';

        public function __construct() {
               
        }

        public function __destruct() {
        	
			
        }
		
		public function setUser(){
			elgg_register_plugin_hook_handler('logged_in_user', 'user', function(){
				try{
					$user = new Minds\Entities\user('unit');
				}catch(Exception $e){
					$user = new Minds\Entities\user();
					$user->username = 'unit';
					$user->name = 'Unit Tester';
					$user->email = 'unit@minds.com';
					$user->save();
				}
				return $user;
			});
		}

}