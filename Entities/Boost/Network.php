<?php
/**
 * Network Boost Entity
 */
namespace Minds\Entities\Boost;

use Minds\Core;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Entities\Entity;
use Minds\Entities\User;
use Minds\Helpers;

class Network extends Entities\DenormalizedEntity implements BoostEntityInterface
{

    public $type = 'boost';
    public $subtype = 'network';

    protected $_id; //specific to mongo related
    protected $entity;
    protected $bid;
    protected $owner;
    protected $state = 'created';
    protected $time_created;
    protected $last_updated;
    protected $transactionId;
    protected $handler = 'newsfeed';
    protected $bidType = 'points';

    protected $exportableDefaults = [
      'guid', '_id', 'entity', 'bid', 'bidType', 'destination', 'owner', 'state',
      'transactionId', 'time_created', 'last_updated', 'type', 'subtype', 'handler'
    ];

    /**
     * Load from the database
     * @param $guid
     * @return $this
     */
    public function loadFromDB($guid)
    {
        throw new \Exception("Can not load a boost pro directly from the database, please loadFromArray()");
    }

    /**
     * Load from an array
     * @param array $array
     * @return $this
     */
    public function loadFromArray($array)
    {
        $this->guid = $array['guid'];
        $this->_id = $array['_id'];
        $this->entity = Entities\Factory::build($array['entity']);
        $this->bid = $array['bid'];
        $this->bidType = $array['bidType'];
        $this->owner = Entities\Factory::build($array['owner']);
        $this->state = $array['state'];
        $this->time_created = $array['time_created'];
        $this->last_updated = $array['last_updated'];
        $this->transactionId = $array['transactionId'];
        $this->handler = $array['handler'];
        return $this;
    }

    /**
     * Save to the database
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
          'bidType' => $this->bidType,
          'owner' => $this->owner->export(),
          'state' => $this->state,
          'time_created' => $this->time_created ?: time(),
          'last_updated' => time(),
          'transactionId' => $this->transactionId,
          'handler' => $this->handler
        ];

        $serialized = json_encode($data);
        $this->db->insert("boost:$this->handler", [ $this->guid => $serialized ]);
        $this->db->insert("boost:$this->handler:requested:{$this->owner->guid}", [ $this->guid => $serialized ]);
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
    public function setEntity(Entity $entity)
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
        $guid = $this->entity->guid;
        if($this->entity->entity_guid){
            $guid = $this->entity->entity_guid;
        }
        $this->entity->{'thumbs:up:user_guids'} = array_keys($this->db->getRow("thumbs:up:entity:$guid", [ 'offset'=> Core\Session::getLoggedInUserGuid() ]));
        $this->entity->{'thumbs:down:user_guids'} = array_keys($this->db->getRow("thumbs:down:entity:$guid", [ 'offset'=> Core\Session::getLoggedInUserGuid() ]));
        return $this->entity;
    }

    /**
     * Set the owner
     * @param Entity $owner
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
     * Return the bid
     * @return int
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
     */
    public function setBidType($type)
    {
        $this->bidType = $type;
        return $this;
    }

    /**
     * Set the boost handler (eg. Newsfeed, Content)
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

    public function export()
    {
        $export = parent::export();
        $export = array_merge($export, \Minds\Core\Events\Dispatcher::trigger('export:extender', 'all', array('entity'=>$this), array()));
        $export = \Minds\Helpers\Export::sanitize($export);
        return $export;
    }
}
