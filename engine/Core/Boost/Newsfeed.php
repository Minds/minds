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
     * @param object $entity - the entity to boost
     * @param int $impressions
     * @return boolean
     */
    public function boost($entity, $impressions){
        $db = new Data\Call('entities_by_time');
        return $db->insert("boost:newsfeed:review", array($entity->guid => $impressions));
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
        $db = new Data\Call('entities_by_time');
        return $db->insert("boost:newsfeed", array($entity->guid => $impressions));
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
