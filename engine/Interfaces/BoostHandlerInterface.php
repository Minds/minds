<?php
namespace Minds\Interfaces;

/**
 * Interface for Boost Handlers
 */
interface BoostHandlerInterface{

    /**
     * Boost an entity, place in a review queue first
     * @param object/int $entity - the entity to boost
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
     * @param object/int $entity
     * @param int impressions
     * @return boolean
     */
    public function accept($entity, $impressions);

    /**
     * Return a boost
     * @return array
     */
    public function getBoost();
}
