<?php
namespace Minds\Core\Boost;
use Minds\interfaces\BoostHandlerInterface;
use Minds\Core;
use Minds\Core\Data;
use Minds\Helpers;

/**
 * Newsfeed Boost handler
 */
class Newsfeed implements BoostHandlerInterface{
	
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
        return $db->insert("boost:newsfeed:review", array($guid => $impressions));
    }
    
     /**
     * Return boosts for review
     * @param int $limit
     * @param string $offset
     * @return array
     */
    public function getReviewQueue($limit, $offset = ""){
        $db = new Data\Call('entities_by_time');
        $guids = $db->getRow("boost:newsfeed:review", array('limit'=>$limit, 'offset'=>$offset, 'reversed'=>false));
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
        $accept = $db->insert("boost:newsfeed", array($guid => $impressions));
        if($accept){
            //remove from review
            $db->removeAttributes("boost:newsfeed:review", array($guid));
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
        $db->removeAttributes("boost:newsfeed:review", array($guid));

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
        $cacher = Core\Data\cache\factory::build();
        $db = new Data\Call('entities_by_time');
        $mem_log =  $cacher->get(Core\session::getLoggedinUser()->guid . ":seenboosts") ?: array();
          
        $boosts = $db->getRow("boost:newsfeed", array('limit'=>15));
        if(!$boosts){
            return null;
        }
        foreach($boosts as $boost => $impressions){
            if(in_array($boost, $mem_log)){
                continue; // already seen
            }
            //increment impression counter
            Helpers\Counters::increment($boost, "boost_impressions", 1);
            //get the current impressions count for this boost
            Helpers\Counters::increment(0, "boost_impressions", 1);
            $count = Helpers\Counters::get($boost, "boost_impressions", false); 
            if($count > $impressions){
                //remove from boost queue
                $db->removeAttributes("boost:newsfeed", array($boost));
                $entity = new \Minds\entities\activity($boost);
                Core\Events\Dispatcher::trigger('notification', 'elgg/hook/activity', array(
                'to'=>array($entity->owner_guid),
                'from'=> 100000000000000519,
                'object_guid' => $boost,
                'title' => $entity->title,
                'notification_view' => 'boost_completed',
                'params' => array('impressions'=>$impressions),
                'impressions' => $impressions
                ));
                continue; //max count met
            }
            array_push($mem_log, $boost);
            $cacher->set(Core\session::getLoggedinUser()->guid . ":seenboosts", $mem_log, (12 * 3600));
            return $boost;
        }
    }
        
}
