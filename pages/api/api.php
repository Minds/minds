<?php
/**
 * Minds API - pseudo router
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api;

use Minds\Core;
use minds\interfaces;
use Minds\Api\Factory;

class api implements interfaces\api{

	public function get($pages){
        
        return Factory::build($pages);
        
	}
	
	public function post($pages){
	    
        return Factory::build($pages);
        
	}
	
	public function put($pages){
	    
        return Factory::build($pages);
        
	}
	
	public function delete($pages){
	    
        return Factory::build($pages);
        
	}
	
}
