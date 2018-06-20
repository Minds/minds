<?php

namespace Minds\Core\Boost\Peer;

use Minds\Core;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Helpers\MagicAttributes;
use Minds\Interfaces\BoostReviewInterface;

class Review implements BoostReviewInterface
{
    /** @var  Entities\Boost\Peer $boost */
    protected $boost;
    protected $mongo;
    protected $type;

    public function __construct(Data\Interfaces\ClientInterface $mongo = null)
    {
        $this->mongo = $mongo ?: Data\Client::build('MongoDB');
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param Entities\Boost\Peer $boost
     * @return $this
     */
    public function setBoost($boost)
    {
        $this->boost = $boost;
        return $this;
    }

    /**
     * Accept a boost and do a remind
     * @return bool
     * @throws \Exception
     */
    public function accept()
    {
        if (!$this->boost) {
            throw new \Exception('Boost wasn\'t set');
        }
        if (!$this->boost instanceof Entities\Boost\Peer) {
            $this->boost = $this->getBoostEntity($this->boost);
        }

        $this->boost->setState('accepted')
            ->save();

        return true;
    }

    /**
     * Reject a boost
     * @return bool
     * @throws \Exception
     */
    public function reject($reason = null)
    {
        if (!$this->boost) {
            throw new \Exception('Boost wasn\'t set');
        }
        if (!$this->boost instanceof Entities\Boost\Peer) {
            $this->boost = $this->getBoostEntity($this->boost);
        }

        $this->boost->setState('rejected')
            ->save();

        return true;
    }

    /**
     * Revoke a boost
     * @throws \Exception
     */
    public function revoke()
    {
        if (!$this->boost) {
            throw new \Exception('Boost wasn\'t set');
        }
        if (!$this->boost instanceof Entities\Boost\Peer) {
            $this->boost = $this->getBoostEntity($this->boost);
        }

        $this->boost->setState('revoked')
            ->save();
    }

    protected function enableBoostRejectionReasonFlag($entity = null, $reason = -1)
    {
        if (!$entity || !is_object($entity)) {
            return false;
        }

        $dirty = false;

        // Main boost rejection reason flag
        if (MagicAttributes::setterExists($entity, 'setBoostRejectionReason')) {
            $entity->setBoostRejectionReason($reason);
            $dirty = true;
        } elseif (property_exists($entity, 'boost_rejection_reason')) {
            $entity->boost_rejection_reason = true;
            $dirty = true;
        }

        return $dirty;
    }

    /**
     * Gets a single boost entity
     * @param  mixed $guid
     * @return Entities\Boost\BoostEntityInterface
     */
    public function getBoostEntity($guid)
    {
        /** @var Core\Boost\Repository $repository */
        $repository = Core\Di\Di::_()->get('Boost\Repository');
        return $repository->getEntity('peer', $guid);
    }

    /**
     * Return all peer boosts
     * @param string $destination_guid
     * @param  int $limit
     * @param  string $offset
     * @param string $handler
     * @return array
     * @internal param string $peer
     */
    public function getReviewQueue($limit, $offset = "")
    {
        /** @var Core\Boost\Repository $repository */
        $repository = Core\Di\Di::_()->get('Boost\Repository');
        $boosts = $repository->getAll('peer', [
            'destination_guid' => $this->type,
            'limit' => $limit,
            'offset' => $offset,
            'order' => 'ASC'
        ]);

        return $boosts;
    }

    /**
     * Get a user's own submitted Boosts
     * @param string $guid
     * @param  int $limit
     * @param  string $offset
     * @return array
     */
    public function getOutbox($guid, $limit, $offset = "")
    {
        /** @var Core\Boost\Repository $repository */
        $repository = Core\Di\Di::_()->get('Boost\Repository');
        $boosts = $repository->getAll('peer', [
            'owner_guid' => $guid,
            'limit' => $limit,
            'offset' => $offset,
            'order' => 'DESC'
        ]);

        return $boosts;
    }
}
