<?php

namespace minds\plugin\social\pages;

use Minds\Core;
use minds\interfaces;
use minds\plugin\social\services;

class authorize extends core\page implements Interfaces\page{
		
	/**
	 * Get requests
	 */
	public function get($pages){
	
		if(!isset($pages[0])){
			return false;
		}

        if($_REQUEST['access_token']){
            setcookie('loggedin', 1, time() + (60 * 60 * 24 * 30), '/'); 
            $_SESSION['user'] = core\Session::getLoggedinUser(); //hate this hack..    
        }

		try{
			$service = services\build::build($pages[0]);
		}catch(\Exception $e){
			return false;
		}
		
		$this->forward($service->authorizeURL());
		
	}
		

	public function post($pages){}
	
	public function put($pages){}
	
	public function delete($pages){}
	
}
    
