<?php

namespace Minds\Core\Boost;

use Minds\Core;
use Minds\Core\Data;
use Minds\Helpers;
use minds\interfaces;
/**
 * Suggested boost handler
 */
class Suggested implements interfaces\BoostHandlerInterface{
    
   /**
     * Boost an entity
     * @param object/int $entity - the entity to boost
     * @param int $impressions
     * @return boolean
     */
    public function boost($entity, $impressions){
        if(is_object($entity)){
            $guid = $entity->guid;
        } else {
            $guid = $entity;
        }
        $db = new Data\Call('entities_by_time');
        return $db->insert("boost:suggested:review", array($guid => $impressions));
    }
    
     /**
     * Return boosts for review
     * @param int $limit
     * @param string $offset
     * @return array
     */
    public function getReviewQueue($limit, $offset = ""){
        $db = new Data\Call('entities_by_time');
        $guids = $db->getRow("boost:suggested:review", array('limit'=>$limit, 'offset'=>$offset));
        return $guids;
    }
    
    /**
     * Accept a boost
     * @param object/int $entity
     * @param int impressions
     * @return boolean
     */
    public function accept($entity, $impressions){
        if(is_object($entity)){
            $guid = $entity->guid;
        } else {
            $guid = $entity;
        }
        $db = new Data\Call('entities_by_time');
        $accept = $db->insert("boost:suggested", array($guid => $impressions));
        if($accept){
            //remove from review
            $db->removeAttributes("boost:suggested:review", array($guid));
            //clear the counter for boost_impressions
            Helpers\Counters::clear($guid, "boost_impressions");
            
            $entity = new \Minds\entities\activity($guid);
            Core\Events\Dispatcher::trigger('notification', 'elgg/hook/activity', array(
                'to'=>array($entity->owner_guid),
                'object_guid' => $guid,
                'title' => $entity->title,
                'notification_view' => 'boost_accepted',
                'params' => array('impressions'=>$impressions),
                'impressions' => $impressions
                ));
        }
        return $accept;
    }

    /**
     * Reject a boost
     * @param object/int $entity
     * @return boolean
     */
    public function reject($entity){
        if(is_object($entity)){
            $guid = $entity->guid;
        } else {
            $guid = $entity;
        }
        $db = new Data\Call('entities_by_time');
        $db->removeAttributes("boost:suggested:review", array($guid));

        $entity = new \Minds\entities\activity($guid);
            Core\Events\Dispatcher::trigger('notification', 'elgg/hook/activity', array(
                'to'=>array($entity->owner_guid),
                'object_guid' => $guid,
                'title' => $entity->title,
                'notification_view' => 'boost_rejected',
                ));
        return true;//need to double check somehow..
    }
    
    /**
     * Return a boost
     * @return array
     */
    public function getBoost($offset = ""){
        $db = new Data\Call('entities_by_time');
          
        $boosts = $db->getRow("boost:suggested", array('limit'=>15));
        if(!$boosts){
            return null;
        }

        $prepared = new Data\Neo4j\Prepared\Common();
        $result= Data\Client::build('Neo4j')->request($prepared->getActed(array_keys($boosts)));
        $rows = $result->getRows();

        foreach($boosts as $boost => $impressions){
            $seen = false;
            foreach($rows['items'] as $item){
                if($item['guid'] == $boost)
                       $seen = true; 
            }
            if($seen)
                continue;

            //get the current impressions count for this boost
            $count = Helpers\Counters::get($boost, "boost_swipes", false); 
            if($count > $impressions){
                //remove from boost queue
                $db->removeAttributes("boost:suggested", array($boost));
                $entity = new \Minds\entities\activity($boost);
                Core\Events\Dispatcher::trigger('notification', 'elgg/hook/activity', array(
                'to'=>array($entity->owner_guid),
                'object_guid' => $boost,
                'title' => $entity->title,
                'notification_view' => 'boost_completed',
                'params' => array('impressions'=>$impressions),
                'impressions' => $impressions
                ));
                continue; //max count met
            }
            return $boost;
        }
    }
        
}
