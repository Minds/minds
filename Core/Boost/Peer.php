<?php
namespace Minds\Core\Boost;

use Minds\Core;
use Minds\Core\Di\Di;
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
     * @param int|object $entity
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
        /** @var Repository $repository */
        $repository = Di::_()->get('Boost\Repository');
        $boosts = $repository->getAll('peer', [
            'destination_guid' => $this->guid,
            'limit' => $limit,
            'offset' => $offset,
            'order' => 'ASC'
        ]);

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
        /** @var Repository $repository */
        $repository = Di::_()->get('Boost\Repository');
        $boosts = $repository->getAll('peer', [
            'owner_guid' => Core\Session::getLoggedinUser()->guid,
            'limit' => $limit,
            'offset' => $offset,
            'order' => 'DESC'
        ]);

        return $boosts;
    }

    /**
     * Gets a single boost entity
     * @param  mixed  $guid
     * @return object
     */
    public function getBoostEntity($guid)
    {
        /** @var Repository $repository */
        $repository = Di::_()->get('Boost\Repository');
        return $repository->getEntity('peer', $guid);
    }

    /**
     * Accept a boost and do a remind
     * @param int|object $boost
     * @param  int $impressions
     * @return bool
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
     * @param int|object $boost
     * @return bool
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
     * @param int|object $boost
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
     * @deprecated
     * @return array
     */
    public function getBoost($offset = "")
    {

       ///
       //// THIS DOES NOT APPLY BECAUSE IT'S PRE-AGREED
       ///
    }
}
