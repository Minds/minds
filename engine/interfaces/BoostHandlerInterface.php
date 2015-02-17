<?php
namespace minds\interfaces;

/**
 * Interface for Boost Handlers
 */
interface BoostHandlerInterface{
	
    /**
     * Boost an entity, place in a review queue first
     * @param object $entity - the entity to boost
     * @param int $impressions
     * @return boolean
     */
	public function boost($entity, $impressions);
	
    
    /**
     * Return boosts for review
     * @param int $limit
     * @param string $offset
     * @return array
     */
    public function getReviewQueue($limit, $offset = "");
    
    /**
     * Accept a boost
     * @param object $entity
     * @param int impressions
     * @return boolean
     */
    public function accept($entity, $impressions){
    }
    
    /**
     * Return a boost
     * @return array
     */
    public function getBoost();
}
