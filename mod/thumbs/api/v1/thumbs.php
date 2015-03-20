<?php
/**
 * Minds Thumbs API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\thumbs\api\v1;

use Minds\Core;
use minds\interfaces;
use minds\entities;
use Minds\Api\Factory;
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
            return Factory::response(array('status'=>'error', 'message'=>'entity not found'));

        $response = array();
        $response['count'] = $entity->{'thumbs:up:count'};

        return Factory::response($response);
        
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
        
        if($entity->guid){
            if(helpers\buttons::hasThumbed($entity, $direction)){
	            helpers\storage::cancel($direction, $entity);
                \Minds\plugin\payments\start::createTransaction(Core\session::getLoggedinUser()->guid, -1, $guid, 'vote removed');
            } else {
	            helpers\storage::insert($direction, $entity);
                \Minds\plugin\payments\start::createTransaction(Core\session::getLoggedinUser()->guid, 1, $guid, 'vote');
               // \Minds\plugin\payments\start::createTransaction($entity->owner_guid, 1, $guid, 'vote'); 
            }
        }else{
            error_log("Entity $guid not found");
            return Factory::response(array('status'=>'error', 'message'=>'entity not found'));
        }
        
        return Factory::response(array());
        
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
             return Factory::response(array('status'=>'error', 'message'=>'entity not found'));
        
         return Factory::response();
        
    }
    
}
        
