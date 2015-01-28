<?php
/**
 * Minds Suggested API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1\entities;

use Minds\Core;
use Minds\Core\Data;
use minds\entities;
use minds\interfaces;
use minds\api\factory;

class suggested implements interfaces\api{

    /**
     * Returns the entities
     * @param array $pages
     * 
     * API:: /v1/entities/suggested
     */      
    public function get($pages){
        $prepared = new Data\Neo4j\Prepared\Subscriptions();
        $result= Data\Client::build('Neo4j')->request($prepared->getSubscriptionsOfSubscriptions(Core\session::getLoggedInUser()));
        
	$rows = $result->getRows();
        $guids = array();
        foreach($rows['fof'] as $fof){
            $guids[] = $fof['guid'];
        }
	
        if(!$guids){
            return factory::response(array('status'=>'error', 'message'=>'not found'));
        }
        
	   $options['guids'] = $guids;
	   $entities = Core\entities::get($options);

        if($entities){
            $response['entities'] = factory::exportable($entities);
            $response['load-next'] = (string) end($entities)->guid;
            $response['load-previous'] = (string) key($entities)->guid;
        }
        
        return factory::response($response);
        
    }
    
    public function post($pages){
        
        switch($pages[0]){
            case 'pass':
                //users only action. objects should use 'pass'
                $prepared = new Core\Data\Neo4j\Prepared\Subscriptions();
                Core\Data\Client::build('Neo4j')->request($prepared->createPass(Core\session::getLoggedinUser()->guid, $pages[1]));
        }
        
    }
    
    public function put($pages){}
    
    public function delete($pages){}
    
}
        
