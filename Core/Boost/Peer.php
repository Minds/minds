<?php
namespace Minds\Core\Boost;

use Minds\Core;
use Minds\Core\Data;
use Minds\Interfaces;
use Minds\Entities;
use Minds\Helpers;
use Minds\Core\Payments;

/**
 * Peer Boost Handler
 */
class Peer implements Interfaces\BoostHandlerInterface
{
    private $guid;

    public function __construct($options)
    {
        if (isset($options['destination'])) {
            $this->guid = $options['destination'];
        }
    }

   /**
     * Boost an entity. Not used.
     * @param  object/int $entity - the entity to boost
     * @param  int $points
     * @return null
     */
    public function boost($entity, $points)
    {
        return null;
    }

     /**
     * Return all peer boosts
     * @param  int    $limit
     * @param  string $offset
     * @return array
     */
    public function getReviewQueue($limit, $offset = "")
    {
        $db = new Data\Call('entities_by_time');
        $data = (array) $db->getRow("boost:peer:$this->guid", ['limit'=>$limit, 'offset'=>$offset, 'reversed'=>true]);

        $boosts = [];
        foreach ($data as $guid => $raw_data) {
            //$raw_data['guid']
          $boosts[] = (new Entities\Boost\Peer())
            ->loadFromArray(json_decode($raw_data, true));
        }
        return $boosts;
    }

    /**
     * Get our own submitted Boosts
     * @param  int    $limit
     * @param  string $offset
     * @return array
     */
    public function getOutbox($limit, $offset = "")
    {
        $db = new Data\Call('entities_by_time');
        $data = $db->getRow("boost:peer:requested:$this->guid", ['limit'=>$limit, 'offset'=>$offset, 'reversed'=>true]);

        $boosts = [];
        foreach ($data as $guid => $raw_data) {
            //$raw_data['guid']
        $boosts[] = (new Entities\Boost\Peer())
          ->loadFromArray(json_decode($raw_data, true));
        }
        return $boosts;
    }

    /**
     * Gets a single boost entity
     * @param  mixed  $_id
     * @return object
     */
    public function getBoostEntity($_id)
    {
        $db = new Data\Call('entities_by_time');
        $data = $db->getRow("boost:peer:$this->guid", ['limit'=>1, 'offset'=>$_id]);
        if (key($data) != $_id) {
            return false;
        }

        $boost = (new Entities\Boost\Peer($db))
            ->loadFromArray(json_decode($data[$_id], true));

        return $boost;
    }

    /**
     * Accept a boost and do a remind
     * @param  object|int $entity
     * @param  int        $impressions
     * @return boolean
     */
    public function accept($boost, $impressions = 0)
    {
        if (!$boost instanceof Entities\Boost\Peer) {
            $boost = $this->getBoostEntity($boost);
        }

        $boost->setState('accepted')
            ->save();

        return true;
    }

    /**
     * Reject a boost
     * @param  object|int $entity
     * @return boolean
     */
    public function reject($boost)
    {
        if (!$boost instanceof Entities\Boost\Peer) {
            $boost = $this->getBoostEntity($boost);
        }

        $boost->setState('rejected')
        ->save();

        return true;
    }

    /**
     * Revoke a boost
     * @param  object|int $entity
     * @return boolean
     */
    public function revoke($boost)
    {
        if (!$boost instanceof Entities\Boost\Peer) {
            $boost = $this->getBoostEntity($boost);
        }

        $boost->setState('revoked')
        ->save();

        return true;
    }

    /**
     * Return a boost. Not used.
     * @return array
     */
    public function getBoost($offset = "")
    {

       ///
       //// THIS DOES NOT APPLY BECAUSE IT'S PRE-AGREED
       ///
    }
}
