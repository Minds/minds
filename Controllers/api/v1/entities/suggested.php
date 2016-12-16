<?php
/**
 * Minds Suggested API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1\entities;

use Minds\Core;
use Minds\Core\Data;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class suggested implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
     * Returns the entities
     * @param array $pages
     *
     * @SWG\GET(
     *     tags={"entities"},
     *     summary="Returns suggested entities",
     *     path="/v1/entities/suggested/{type}/{subtype}",
     *     @SWG\Parameter(
     *      name="type",
     *      in="path",
     *      description="Type (eg. object, user, activity)",
     *      required=false,
     *      type="string"
     *     ),
     *     @SWG\Parameter(
     *      name="subtype",
     *      in="path",
     *      description="Subtype (eg. video, image, blog)",
     *      required=false,
     *      type="string"
     *     ),
     *     @SWG\Parameter(
     *      name="limit",
     *      in="query",
     *      description="Limit the number of returned entities",
     *      required=false,
     *      type="integer"
     *     ),
     *     @SWG\Parameter(
     *      name="skip",
     *      in="query",
     *      description="How many entities to skip",
     *      required=false,
     *      type="integer"
     *     ),
     *     @SWG\Response(name="200", description="Array")
     * )
     */
    public function get($pages)
    {
        $prepared = new Data\Neo4j\Prepared\Common();
        if (!isset($pages[1])) {
            $pages[1] = $pages[0];
        }
        $ts = microtime(true);
        switch ($pages[1]) {
            case 'videos':
            case 'video':
                $result= Data\Client::build('Neo4j')->requestRead($prepared->getSuggestedObjects(Core\Session::getLoggedInUser()->guid, 'video', $_GET['skip']));
                
                $rows = $result->getRows();
                if (!$rows) {
                    $result= Data\Client::build('Neo4j')->requestRead($prepared->getObjects(Core\Session::getLoggedInUser()->guid, 'video'));
                    $rows = $result->getRows();
                }
 
                $guids = array();
                foreach ($rows['object'] as $object) {
                    $guids[] = $object['guid'];
                }
                if (!$guids) {
                    //show trending videos
                    $options = array(
                        'timespan' => get_input('timespan', 'day')
                        );
                    $trending = new \MindsTrending(null, $options);
                    $guids = $trending->getList(array('type'=>'object', 'subtype'=>'kaltura_video', 'limit'=>6));
                }
                break;
            case 'images':
            case 'image':
                $result= Data\Client::build('Neo4j')->requestRead($prepared->getSuggestedObjects(Core\Session::getLoggedInUser()->guid, 'image', $_GET['skip']));

                $rows = $result->getRows();
                if (!$rows) {
                    $result= Data\Client::build('Neo4j')->requestRead($prepared->getObjects(Core\Session::getLoggedInUser()->guid, 'image'));
                    $rows = $result->getRows();
                }
                
                $guids = array();
                foreach ($rows['object'] as $object) {
                    $guids[] = $object['guid'];
                }
                break;
            case 'user':
            default:
            
                if (isset($_GET['nearby']) && $_GET['nearby'] === "true") {
                    //error_log($_GET['coordinates']);
                    $p = $prepared->getUserByLocation(Core\Session::getLoggedInUser(), isset($_GET['coordinates']) && $_GET['coordinates'] != "false" ? $_GET['coordinates'] : null, isset($_GET['distance']) ? $_GET['distance'] : 25, 12, $_GET['skip']);
                    if ($p) {
                        $result= Data\Client::build('Neo4j')->requestRead($p);
                    } else {
                        $result = false;
                    }
                    if (!$result) {
                        return Factory::response(array('status'=>'error', 'message'=>'not found'));
                    }
                } else {
                    $result= Data\Client::build('Neo4j')->requestRead($prepared->getSubscriptionsOfSubscriptions(Core\Session::getLoggedInUser(), $_GET['skip']));
                }

                $rows = $result->getRows();
                $guids = array();
                if (isset($rows['fof'])) {
                    foreach ($rows['fof'] as $fof) {
                        $guids[] = $fof['guid'];
                    }
                }
        }
    
        if (!$guids) {
            return Factory::response(array('status'=>'error', 'message'=>'not found'));
        }
        
        $options['guids'] = $guids;
 
        $entities = Core\Entities::get($options);
        /*$boost = Core\Boost\Factory::build("Suggested")->getBoost();
        if ($boost && $boost['guid']) {
            $boost_guid = $boost['guid'];
            $boost_object = entities\Factory::build($boost_guid);
            $boost_object->boosted = true;
            array_splice($entities, 2, 0, array($boost_guid => $boost_object));
        }*/

        $diff = microtime(true) - $ts;
        //error_log("loaded suggested entities in $diff");
        if ($entities) {
            $response['entities'] = factory::exportable($entities, array('boosted'));
            $response['load-next'] = (string) end($entities)->guid;
            $response['load-previous'] = (string) key($entities)->guid;
        }
        
        return Factory::response($response);
    }
    
    /**
     * @SWG\POST(
     *     tags={"entities"},
     *     summary="Add a relationship to an entity",
     *     path="/v1/entities/suggested/{action}/{guid}",
     *     @SWG\Parameter(
     *      name="action",
     *      in="path",
     *      description="Action (eg. pass)",
     *      required=true,
     *      type="string"
     *     ),
     *     @SWG\Parameter(
     *      name="guid",
     *      in="path",
     *      description="The entity to create the relationship on",
     *      required=false,
     *      type="integer"
     *     ),
     *     @SWG\Response(name="200", description="Array")
     * )
     */
    public function post($pages)
    {
        switch ($pages[0]) {
            case 'pass':
                $prepared = new Core\Data\Neo4j\Prepared\Common();
                Core\Data\Client::build('Neo4j')->request($prepared->createPass((string) Core\Session::getLoggedinUser()->guid, (string) $pages[1]));
                break;
            case 'acted':
                Helpers\Counters::increment($boost, "boost_swipes", 1);
                break;
        }
    }
    
    public function put($pages)
    {
    }
    
    public function delete($pages)
    {
    }
}
