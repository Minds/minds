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
use Minds\Api\Factory;

class suggested implements interfaces\api, interfaces\ApiIgnorePam{

    /**
     * Returns the entities
     * @param array $pages
     * 
     * API:: /v1/entities/suggested
     */      
    public function get($pages){
        $prepared = new Data\Neo4j\Prepared\Common();
        if(!isset($pages[1]))
            $pages[1] = $pages[0];
	error_log("loading suggested entities");
	$ts = microtime(true);
        switch($pages[1]){
            case 'video':
                $result= Data\Client::build('Neo4j')->request($prepared->getSuggestedObjects(Core\session::getLoggedInUser()->guid, 'video', $_GET['skip']));
                
                $rows = $result->getRows();
                if(!$rows){
                    $result= Data\Client::build('Neo4j')->request($prepared->getObjects(Core\session::getLoggedInUser()->guid, 'video'));
                    $rows = $result->getRows();
                }              
 
                $guids = array();
                foreach($rows['object'] as $object){
                    $guids[] = $object['guid'];
                }
                if(!$guids){
                    //show trending videos
                    $options = array(
                        'timespan' => get_input('timespan', 'day')
                        );
                    $trending = new \MindsTrending(null, $options);
                    $guids = $trending->getList(array('type'=>'object', 'subtype'=>'kaltura_video', 'limit'=>6));
                }
                break;
            case 'image':
                $result= Data\Client::build('Neo4j')->request($prepared->getSuggestedObjects(Core\session::getLoggedInUser()->guid, 'image', $_GET['skip']));

                $rows = $result->getRows();
                if(!$rows){
                    $result= Data\Client::build('Neo4j')->request($prepared->getObjects(Core\session::getLoggedInUser()->guid, 'image'));
                    $rows = $result->getRows();
                }
                
                $guids = array();
                foreach($rows['object'] as $object){
                    $guids[] = $object['guid'];
                }
                break; 
            case 'user':
            default:
                
                $result= Data\Client::build('Neo4j')->request($prepared->getSubscriptionsOfSubscriptions(Core\session::getLoggedInUser(), $_GET['skip']));
                
                $rows = $result->getRows();
                $guids = array();
                foreach($rows['fof'] as $fof){
                    $guids[] = $fof['guid'];
                }
        }
	
        if(!$guids){
            return Factory::response(array('status'=>'error', 'message'=>'not found'));
        }
        
	    $options['guids'] = $guids;
 
        $entities = Core\entities::get($options);
        $boost_guid = Core\Boost\Factory::build("Suggested")->getBoost();
        if($boost_guid){
            $boost_guid = $boost_guid;
            $boost_object = entities\Factory::build($boost_guid);
            $boost_object->boosted = true;
            array_splice($entities, 2, 0, array($boost_guid => $boost_object));
        }

        $diff = microtime(true) - $ts;
    	error_log("loaded suggested entities in $diff");
        if($entities){
            $response['entities'] = factory::exportable($entities, array('boosted'));
            $response['load-next'] = (string) end($entities)->guid;
            $response['load-previous'] = (string) key($entities)->guid;
        }
        
        return Factory::response($response);
        
    }
    
    public function post($pages){
        
        switch($pages[0]){
            case 'pass':
                $prepared = new Core\Data\Neo4j\Prepared\Common();
                Core\Data\Client::build('Neo4j')->request($prepared->createPass(Core\session::getLoggedinUser()->guid, $pages[1]));
                \Minds\plugin\payments\start::createTransaction(Core\session::getLoggedinUser()->guid, 1, $pages[1], 'pass');
        }
        
    }
    
    public function put($pages){}
    
    public function delete($pages){}
    
}
        
