<?php

namespace Minds\Entities\Boost;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Entities\Entity;
use Minds\Entities\User;
use Minds\Helpers\Counters;

/**
 * Network Boost Entity
 */
class Network extends Entities\DenormalizedEntity implements BoostEntityInterface
{
    public $type = 'boost';
    public $subtype = 'network';

    protected $_id; //specific to mongo related
    protected $entity;
    protected $bid;
    protected $impressions;
    protected $owner;
    protected $state = 'created';
    protected $time_created;
    protected $last_updated;
    protected $transactionId;
    protected $handler = 'newsfeed';
    protected $bidType = 'points';
    protected $priorityRate = 0;
    protected $rating;
    protected $quality = 75;
    protected $categories = [];
    protected $rejection_reason = -1;
    protected $checksum = null;

    protected $exportableDefaults = [
        'guid', '_id', 'entity', 'bid', 'bidType', 'destination', 'owner', 'state',
        'transactionId', 'time_created', 'last_updated', 'type', 'subtype', 'handler',
        'rating', 'quality', 'impressions', 'categories', 'rejection_reason', 'checksum'
    ];

    public function __construct($db = null)
    {
        $this->db = null;
    }

    /**
     * Loads from the database using a GUID
     * @param  $guid
     * @throws \Exception
     */
    public function loadFromDB($guid)
    {
        throw new \Exception("Can not load a boost pro directly from the database, please loadFromArray()");
    }

    /**
     * Loads from an array
     * @param  array $array
     * @return $this
     */
    public function loadFromArray($array)
    {
        $array = is_array($array) ? $array : json_decode($array, true);

        $this->guid = $array['guid'];
        $this->_id = $array['_id'];
        $this->entity = Entities\Factory::build($array['entity']);
        $this->bid = $array['bid'];
        $this->bidType = $array['bidType'];
        $this->impressions = $array['impressions'];
        $this->owner = Entities\Factory::build($array['owner']);
        $this->state = $array['state'];
        $this->time_created = $array['time_created'];
        $this->last_updated = $array['last_updated'];
        $this->transactionId = $array['transactionId'];
        $this->handler = $array['handler'];
        $this->rating = $array['rating'];
        $this->quality = $array['quality'];
        $this->priorityRate = (float)$array['priorityRate'];
        $this->categories = $array['categories'];
        $this->rejection_reason = $array['rejection_reason'];
        $this->checksum = $array['checksum'];
        return $this;
    }

    /**
     * Write to database
     * @return string - $guid
     */
    public function save()
    {
        if (!$this->guid) {
            $this->guid = Core\Guid::build();
            $this->time_created = time();
        }

        $data = [
            'guid' => $this->guid,
            '_id' => $this->_id,
            'entity' => $this->entity->export(),
            'bid' => $this->bid,
            'impressions' => $this->impressions,
            'bidType' => $this->bidType,
            'owner' => $this->owner->export(),
            'state' => $this->state,
            'time_created' => $this->time_created ?: time(),
            'last_updated' => time(),
            'transactionId' => $this->transactionId,
            'handler' => $this->handler,
            'priorityRate' => $this->priorityRate,
            'rating' => $this->rating,
            'quality'=> $this->getQuality(),
            'categories' => $this->categories,
            'rejection_reason'=> $this->getRejectionReason(),
            'checksum' => $this->getChecksum(),
        ];

        /** @var Core\Boost\Repository $repository */
        $repository = Di::_()->get('Boost\Repository');
        $repository->upsert($this->handler, $data);
        return $this;
    }

    /**
     * Set the GUID of this boost
     * @return $this
     */
    public function setGuid($guid)
    {
        if (!$this->guid) {
            $this->guid = $guid;
            $this->time_created = time();
        }
        return $this;
    }

    /**
     * Get the GUID of this boost
     * @return string
     */
    public function getGuid()
    {
        if (!$this->guid) {
            $this->guid = Core\Guid::build();
            $this->time_created = time();
        }
        return $this->guid;
    }

    /**
     * Set the internal data id
     * @param string $_id
     * @return $this
     */
    public function setId($_id)
    {
        $this->_id = $_id;
        return $this;
    }

    /**
     * Get the internal $id
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set the entity to boost
     * @param Entity $entity
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Get the entity
     * @return Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set the owner
     * @param User $owner
     * @return $this
     */
    public function setOwner(User $owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * Get the owner
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Get the time created
     * @return int
     */
    public function getTimeCreated()
    {
        return $this->time_created ?: time();
    }

    /**
     * Return the bid
     * @return float
     */
    public function getBid()
    {
        return $this->bid;
    }

    /**
     * Set the bid amount
     * @param $bid
     * @return $this
     */
    public function setBid($bid)
    {
        $this->bid = $bid;
        return $this;
    }

    /**
     * Return the bidded impressions
     * @return list
     */
    public function getImpressions()
    {
        return $this->impressions;
    }

    /**
     * Set the bidded impressions amount
     * @param $impressions
     * @return $this
     */
    public function setImpressions($impressions)
    {
        $this->impressions = $impressions;
        return $this;
    }

    /**
     * Set the boost rating of the boost
     * @param string $rating
     * @return $this
     */
    public function setRating($rating)
    {
        $this->rating = (int)$rating;
        return $this;
    }

    /**
     * Return boost rating of the boost
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    public function setQuality($quality)
    {
        $this->quality = (int) $quality;
        return $this;
    }

    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * Set the state of the boost
     * @param string $state
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Return the state of the boost
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set the priority rate
     * @param $priorityRate
     * @return $this
     */
    public function setPriorityRate($priorityRate)
    {
        $this->priorityRate = (float)$priorityRate;
        return $this;
    }

    /**
     * Get the priority rate
     * @return float
     */
    public function getPriorityRate()
    {
        return $this->priorityRate;
    }

    /**
     * Set the categories for this boost
     * @param $categories
     * @return $this
     */
    public function setCategories(array $categories)
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * Get the categories for this boost
     * @return float
     */
    public function getCategories()
    {
        if (is_string($this->categories)) {
            return json_decode($this->categories, true) ?: [];
        }

        return $this->categories ?: [];
    }

    public function setRejectionReason($value = 0) {
        $this->rejection_reason = (int) $value;
        return $this;
    }

    public function getRejectionReason() {
        return $this->rejection_reason;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function setTransactionId($id)
    {
        $this->transactionId = $id;
        return $this;
    }

    /**
     * Return the boost type
     * @return string
     */
    public function getBidType()
    {
        return $this->bidType;
    }

    /**
     * Set the boost type
     * @param $type
     * @return $this
     */
    public function setBidType($type)
    {
        $this->bidType = $type;
        return $this;
    }

    /**
     * Set the boost handler (eg. Newsfeed, Content)
     * @param $handler
     * @return $this
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;
        return $this;
    }

    /**
     * Get the boost handler
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param string $checksum
     * @return $this
     */
    public function setChecksum($checksum)
    {
        $this->checksum = $checksum;
        return $this;
    }

    /**
     * @return string
     */
    public function getChecksum()
    {
        return $this->checksum;
    }



    /**
     * Exports the boost onto an array
     * @param array $keys
     * @return array
     */
    public function export(array $keys = [])
    {
        $this->owner->fullExport = false; //don't grab counts etc
        $export = parent::export();
        $export = array_merge($export, \Minds\Core\Events\Dispatcher::trigger('export:extender', 'all', array('entity' => $this), array()));
        $export = \Minds\Helpers\Export::sanitize($export);

        $export['met_impressions'] = Counters::get((string) $this->getId(), "boost_impressions");
        return $export;
    }
}
