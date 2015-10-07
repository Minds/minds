<?php
/**
 * Minds cluster user authentication
 */
namespace minds\pages\clusters;

use Minds\Core;
use Minds\Interfaces;
use Minds\Entities;

class authenticate extends core\page implements Interfaces\page{
	
	public $csrf = false; //ignore CSRF as we can't avoid. 
	
	public function get($pages){}
	
	public function post($pages){
		//check to see if a user exists..
		$result = \elgg_authenticate(get_input('username'), get_input('password'));
		if ($result !== true) {
			
			$return = array('error'=>'Could not authenticate');
			
		}else {
			
			$user = new Entities\User(get_input('username'));
			
			$return = $user->export();
			$return['email'] = $user->getEmail();
			
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
