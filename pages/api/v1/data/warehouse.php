<?php
/**
 * Minds  Data Warehouse Runner
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1\data;

use Minds\Core;
use minds\entities;
use minds\interfaces;
use minds\api\factory;

class warehouse implements interfaces\api{

    /**
     * Data warehouse
     * 
     * API:: /v1/data/warehouse
     */      
    public function get($pages){
        $start = microtime();
        \Minds\Core\Data\Warehouse\Factory::build(array_shift($pages))->run($pages);
        $end = microtime();
        
        return factory::response(array('took'=>$end-$start));
    }
    
    public function post($pages){}
    
    public function put($pages){
        
        return factory::response(array());
        
    }
    
    public function delete($pages){
	$activity = new entities\activity($pages[0]); 
	if(!$activity->guid)
		return factory::response(array('status'=>'error', 'message'=>'could not find activity post'));      
 
        return factory::response(array());
        
    }
    
}
        
