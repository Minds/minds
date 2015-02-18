<?php
namespace Minds\Core\Boost;
use Minds\interfaces\BoostHandlerInterface;
use Minds\Core\Data;

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
        $guids = $db->getRow("boost:newsfeed:review", array('limit'=>$limit, 'offset'=>$offset));
        return $guids;
    }
    
    /**
     * Accept a boost
     * @param object $entity
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
        }
        return $accept;
    }
    
    /**
     * Return a boost
     * @return array
     */
    public function getBoost(){
        $guids = $db->getRow("boost:newsfeed", array('limit'=>1));
        return reset($guids);
    }
        
}
