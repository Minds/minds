<?php

namespace Minds\Core\Boost\Network;

use Minds\Core;
use Minds\Core\Data;
use Minds\Entities\Boost\BoostEntityInterface;
use Minds\Entities\Boost\Network;
use Minds\Helpers\MagicAttributes;
use Minds\Interfaces\BoostReviewInterface;
use MongoDB\BSON;

class Review implements BoostReviewInterface
{
    /** @var  Network $boost */
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
        $this->type = strtolower($type);
        return $this;
    }

    /**
     * @param Network $boost
     * @return $this
     */
    public function setBoost($boost)
    {
        $this->boost = $boost;
        return $this;
    }

    /**
     * Accept a boost
     * @return bool
     * @throws \Exception
     * @internal param mixed $_id
     * @internal param int $impressions Optional. Defaults to 0.
     */
    public function accept()
    {
        if (!$this->boost) {
            throw new \Exception('Boost wasn\'t set');
        }
        $success = Core\Di\Di::_()->get('Boost\Payment')->charge($this->boost);
        if ($success) {
            $accept = $this->mongo->update("boost", ['_id' => $this->boost->getId()], [
                'state' => 'approved',
                'rating' => $this->boost->getRating(),
                'quality' => $this->boost->getQuality(),
                'approvedAt' => new BSON\UTCDateTime(time() * 1000)
            ]);
            $this->boost->setState('approved');
            if ($accept) {
                //remove from review
                //$db->removeAttributes("boost:newsfeed:review", array($guid));
                //clear the counter for boost_impressions
                //Helpers\Counters::clear($guid, "boost_impressions");

                /*Core\Events\Dispatcher::trigger('notification', 'boost', [
                    'to'=> [ $boost->getOwner()->guid ],
                    'entity' => $boost->getEntity(),
                    'from'=> 100000000000000519,
                    'title' => $boost->getEntity()->title,
                    'notification_view' => 'boost_accepted',
                    'params' => ['impressions' => $boost->getBid()],
                    'impressions' => $boost->getBid()
                  ]);*/
                $this->boost->save();
            }
            return $accept;
        } else {
            throw new \Exception('error while accepting the boost');
        }
    }

    /**
     * Reject a boost
     * @param  int $reason
     * @throws \Exception
     */
    public function reject($reason)
    {
        if (!$this->boost) {
            throw new \Exception('Boost wasn\'t set');
        }

        $this->boost->setRejectionReason($reason);
        $entity = $this->boost->getEntity();

        $dirty = $this->enableBoostRejectionReasonFlag($entity, $reason);

        try {
            $this->mongo->remove("boost", ['_id' => $this->boost->getId()]);
            $this->boost->setState('rejected')
                ->save();

            Core\Events\Dispatcher::trigger('notification', 'boost', [
                'to' => [$this->boost->getOwner()->guid],
                'from' => 100000000000000519,
                'entity' => $this->boost->getEntity(),
                'params' => [
                    'reason' => $this->boost->getRejectionReason(),
                    'title' => $this->boost->getEntity()->title ?: $this->boost->getEntity()->message
                ],
                'notification_view' => 'boost_rejected',
            ]);

            Core\Di\Di::_()->get('Boost\Payment')->refund($this->boost);
        } catch (\Exception $e) {
            throw new \Exception('error while rejecting the boost');
        }

        if ($dirty) {
            $entity->save();
        }
    }

    /**
     * Revoke a boost
     * @return bool
     * @throws \Exception
     */
    public function revoke()
    {
        if (!$this->boost) {
            throw new \Exception('Boost wasn\'t set');
        }
        $this->mongo->remove("boost", ['_id' => $this->boost->getId()]);
        $this->boost->setState('revoked')
            ->save();

        Core\Events\Dispatcher::trigger('notification', 'boost', [
            'to' => [$this->boost->getOwner()->guid],
            'from' => 100000000000000519,
            'entity' => $this->boost->getEntity(),
            'params' => [
                'title' => $this->boost->getEntity()->title ?: $this->boost->getEntity()->message
            ],
            'notification_view' => 'boost_revoked',
        ]);
        return true;
    }

    /**
     * Gets a single boost entity
     * @param  mixed $guid
     * @return BoostEntityInterface
     */
    public function getBoostEntity($guid)
    {
        /** @var Core\Boost\Repository $repository */
        $repository = Core\Di\Di::_()->get('Boost\Repository');
        return $repository->getEntity($this->type, $guid);
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
        $boosts = $repository->getAll($this->type, [
            'owner_guid' => $guid,
            'limit' => $limit,
            'offset' => $offset,
            'order' => 'DESC'
        ]);

        return $boosts;
    }

    /**
     * Return boosts for review
     * @param  int $limit
     * @param  string $offset
     * @return array
     */
    public function getReviewQueue($limit, $offset = "")
    {
        $query = ['state' => 'review', 'type' => $this->type];
        if ($offset) {
            $query['_id'] = ['$gt' => $offset];
        }
        $queue = $this->mongo->find("boost", $query, [
            'limit' => $limit,
            'sort' => (object) ['priority' => (int) -1, '_id' => 1],
        ]);
        if (!$queue) {
            return null;
        }

        $guids = [];
        $end = "";
        foreach ($queue as $data) {
            $_id = (string) $data['_id'];
            $guids[$_id] = (string) $data['guid'];
            //$this->mongo->remove("boost", ['_id' => $_id]);
        }

        if (!$guids) {
            return [
                'data' => [],
                'next' => ''
            ];
        }

        /** @var Core\Boost\Repository $repository */
        $repository = Core\Di\Di::_()->get('Boost\Repository');
        $boosts = $repository->getAll($this->type, [
            'guids' => $guids
        ]);

        return [
            'data' => $boosts['data'],
            'next' => $_id
        ];
    }

    /**
     * Return the review count
     * @return int
     */
    public function getReviewQueueCount()
    {
        $query = ['state' => 'review', 'type' => $this->type];
        $count = $this->mongo->count("boost", $query);
        return $count;
    }
}
