<?php
/**
 * Minds Thumbs API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\thumbs\api\v1;

use minds\core;
use minds\interfaces;
use minds\entities;
use minds\api\factory;
use minds\plugin\thumbs\helpers;

class thumbs implements interfaces\api{

    /**
     * Return the thumbs information for an entity
     * @param array $pages
     * 
     * API:: /v1/thumbs/:guid
     */      
    public function get($pages){
        
        $guid = $pages[0];
        $direction = $pages[1];
        
        $entity = core\entities::build(new \minds\entities\entity($guid));
        if(!$entity->guid)
            return factory::response(array('status'=>'error', 'message'=>'entity not found'));

        $response = array();
        $response['count'] = $entity->{'thumbs:up:count'};

        return factory::response($response);
        
    }
    
    /**
     * Set a thumb for an entity
     * @param array $pages
     * 
     * API:: /v1/thumbs/:guid/:direction
     */
    public function post($pages){
        
        $guid = $pages[0];
        $direction = $pages[1];
        
        $entity = core\entities::build(new \minds\entities\entity($guid));
        
        if($entity->guid)
            helpers\storage::insert($direction, $entity);
        else
             return factory::response(array('status'=>'error', 'message'=>'entity not found'));
        
         return factory::response(array());
        
    }
    
    public function put($pages){
        
        $this->post($pages);
        
    }
    
    /**
     * Cancel a thumb for an entity
     * @param array $pages
     * 
     * API:: /v1/thumbs/:guid/:direction
     */
    public function delete($pages){
        
        $guid = $pages[0];
        $direction = $pages[1];
        
        $entity = core\entities::build(new \minds\entities\entity($guid));
        
        if($entity->guid)
            helpers\storage::cancel($direction, $entity);
        else
             return factory::response(array('status'=>'error', 'message'=>'entity not found'));
        
         return factory::response();
        
    }
    
}
        
