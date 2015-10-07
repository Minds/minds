<?php
/**
 * Minds Entity
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1\entities;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class entity implements Interfaces\Api{

    /**
     * Returns the entities
     * @param array $pages
     * 
     * @SWG\GET(
     *     tags={"entities"},
     *     summary="Returns a single entity",
     *     path="/v1/entities/entity/{guid}",
     *     @SWG\Parameter(
     *      name="guid",
     *      in="path",
     *      description="Guid",
     *      required=false,
     *      type="integer"
     *     ),
     *     @SWG\Response(name="200", description="Array")
     * )
     */      
    public function get($pages){
        
        if(!isset($pages[0])){
            $response['status'] = 'error';
        } else {
            $entity = Core\Entities::build(new Entities\Entity($pages[0]));
            if($entity instanceof \ElggEntity){
                $response['entity'] = $entity->export();
                $response['entity']['guid'] = (string) $entity->guid;
                if($entity->entityObj){
                    $response['entity']['entityObj']['guid'] = (string) $entity->entityObj->guid;
                }
                if($entity->type == "object"){
                    $response['entity']['thumbnail_src'] = $entity->getIconUrl();
                    $response['entity']['perma_url'] = $entity->getURL();
                }
            }
        }

        return Factory::response($response);
        
    }
    
    public function post($pages){}
    
    public function put($pages){}
    
    public function delete($pages){}
    
}
        
