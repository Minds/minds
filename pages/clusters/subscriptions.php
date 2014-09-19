<?php
/**
 * Minds cluster subscriptions
 */
namespace minds\pages\clusters;

use minds\core;
use minds\interfaces;
use minds\entities;

class subscriptions extends core\page implements interfaces\page{
	
	public function get($pages){
		
	}
	
	public function post($pages){
		//check to see if a user exists..
		$result = \elgg_authenticate(get_input('username'), get_input('password'));
		if ($result !== true) {
			
			$return = array('error'=>'Could not authenticate');
			
		}else {
			
			$user = new entities\user(get_input('username'));
			
			$return = $user->export();
			$return['email'] = $user->email;
			
		}
		
		echo json_encode($return);
	}
	
	public function put($pages){
		throw new \Exception('Sorry, the put method is not supported for the page');
	}
	
	public function delete($pages){
		throw new \Exception('Sorry, the delete method is not supported for the page');
	}
	
}
