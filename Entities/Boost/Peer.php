<?php
/**
 * Peer Boost Entity
 */
namespace Minds\Entities\Boost;

use Minds\Core;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Entities\Entity;
use Minds\Entities\User;
use Minds\Helpers;

class Peer implements BoostEntityInterface
{
    private $db;

    public $guid;
    private $entity;
    private $bid;
    private $destination;
    private $owner;
    private $state = 'created';
    private $time_created;
    private $last_updated;
    private $transactionId;
    private $_type = 'pro';
    private $scheduledTs;
    private $postToFacebook = false;

    public function __construct($db = null)
    {
        if ($db) {
            $this->db = $db;
        } else {
            $this->db = new Data\Call('entities_by_time');
        }
    }

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
      $this->_type = $array['type'];
      $this->entity = Entities\Factory::build($array['entity']);
      $this->bid = $array['bid'];
      $this->destination = Entities\Factory::build($array['destination']);
      $this->owner = Entities\Factory::build($array['owner']);
      $this->state = $array['state'];
      $this->time_created = $array['time_created'];
      $this->last_updated = $array['last_updated'];
      $this->transactionId = $array['transactionId'];
      $this->scheduledTs = isset($array['scheduledTs']) ? $array['scheduledTs'] : null;
      $this->postToFacebook = isset($array['postToFacebook']) ? $array['postToFacebook'] : false;
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
        'type' => $this->_type,
        'entity' => $this->entity->export(),
        'bid' => $this->bid,
        'owner' => $this->owner->export(),
        'destination' => $this->destination->export(),
        'state' => $this->state,
        'time_created' => $this->time_created,
        'last_updated' => time(),
        'transactionId' => $this->transactionId,
        'scheduledTs' => $this->scheduledTs ?: time(),
        'postToFacebook' => $this->postToFacebook
      ];

      $serialized = json_encode($data);
      $this->db->insert("boost:peer:{$this->destination->guid}", [ $this->guid => $serialized ]);
      $this->db->insert("boost:peer:requested:{$this->owner->guid}", [ $this->guid => $serialized ]);
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
      return $this->entity;
  }

  /**
   * Destination
   * @param Entity $destination
   * @return $this
   */
  public function setDestination(User $destination)
  {
      $this->destination = $destination;
      return $this;
  }

  /**
   * Get the destination
   * @return Entity
   */
  public function getDestination()
  {
      return $this->destination;
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
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Set the boost type
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    public function getScheduledTs()
    {
        return $this->scheduledTs ?: time();
    }

    public function setScheduledTs($ts)
    {
        $this->scheduledTs = $ts;
        return $this;
    }

    public function shouldPostToFacebook()
    {
        if($this->postToFacebook){
            return true;
        }
        return false;
    }

    public function postToFacebook($boolean){
        if($boolean){
            $this->postToFacebook = true;
        }
        return $this;
    }

    public function export()
    {
        $export = [
          'guid' => $this->guid,
          'entity' => $this->entity ? $this->entity->export() : [],
          'bid' => $this->bid,
          'bidType' => $this->_type, //move to ->bidType soon
          'destination' => $this->destination ? $this->destination->export() : [],
          'owner' => $this->owner ? $this->owner->export() : [],
          'state' => $this->state,
          'transactionId' => $this->transactionId,
          'time_created' => $this->time_created,
          'last_updated' => $this->last_updated,
          'type' => $this->_type,
          'scheduledTs' => $this->scheduledTs,
          'postToFacebook' => $this->postToFacebook
        ];
        $export = array_merge($export, \Minds\Core\Events\Dispatcher::trigger('export:extender', 'all', array('entity'=>$this), array()));
        $export = \Minds\Helpers\Export::sanitize($export);
        return $export;
    }
}
