<?php
/**
 * Minds Comments API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\comments\api\v1;

use minds\core;
use minds\interfaces;
use minds\api\factory;

class comments implements interfaces\api{

    /**
     * Returns the comments
     * @param array $pages
     * 
     * API:: /v1/comment/:guid
     */      
    public function get($pages){
        
        $response = array();
        $guid = $pages[0];
        
        $indexes = new core\data\indexes('comments');
        $guids = $indexes->get($guid, array('limit'=>\get_input('limit',3), 'offset'=>\get_input('offset',''), 'reversed'=>true));
        if(isset($guids[get_input('offset')]))
            unset($guids[get_input('offset')]);

        if($guids)
            $comments = \elgg_get_entities(array('guids'=>$guids, 'limit'=>\get_input('limit',3), 'offset'=>\get_input('offset','')));
        else 
            $comments = array();

        usort($comments, function($a, $b){ return $a->time_created - $b->time_created;});
	foreach($comments as $k => $comment){
		$owner = $comment->getOwnerEntity();
		$comments[$k]->ownerObj = $owner->export();
	}
        
        $response['comments'] = factory::exportable($comments);
       
    
        return factory::response($response);
        
    }
    
    public function post($pages){
        
    	

        return factory::response($response);
    }
    
    public function put($pages){
        
        return factory::response(array());
        
    }
    
    public function delete($pages){
        
        return factory::response(array());
        
    }
    
}
        
