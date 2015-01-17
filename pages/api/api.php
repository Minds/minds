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
use minds\api\factory;

class api implements interfaces\api{

	public function get($pages){
        
        return factory::build($pages);
        
	}
	
	public function post($pages){
	    
        return factory::build($pages);
        
	}
	
	public function put($pages){
	    
        return factory::build($pages);
        
	}
	
	public function delete($pages){
	    
        return factory::build($pages);
        
	}
	
}
