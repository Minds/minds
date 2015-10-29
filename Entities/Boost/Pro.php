<?php
/**
 * Pro Boost Entity
 */
namespace Minds\Entities\Boost;

use Minds\Core;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Entities\Entity;
use Minds\Entities\User;
use Minds\Helpers;

class Pro implements BoostEntityInterface{

  private $db;

  private $guid;
  private $entity;
  private $bid;
  private $destination;
  private $owner;
  private $state = 'created';
  private $transactionId;

  public function __construct($db = NULL){
    if($db)
      $this->db = $db;
    else
      $this->db = new Data\Call('entities_by_time');
  }

  /**
   * Load from the database
   * @param $guid
   * @return $this
   */
  public function loadFromDB($guid){
    throw new \Exception("Can not load a boost pro directly from the database, please loadFromArray()");
  }

  /**
   * Load from an array
   * @param array $array
   * @return $this
   */
  public function loadFromArray($array){
    $this->guid = $array['guid'];
    $this->entity = Entities\Factory::build($array['entity']);
    $this->bid = $array['bid'];
    $this->destination = Entities\Factory::build($array['destination']);
    $this->owner = Entities\Factory::build($array['owner']);
    $this->state = $array['state'];
    return $this;
  }

  /**
   * Save to the database
   * @return string - $guid
   */
  public function save(){

    if(!$this->guid)
      $this->guid = Core\Guid::build();

    $data = [
      'guid' => $this->guid,
      'entity' => $this->entity->export(),
      'bid' => $this->bid,
      'owner' => $this->owner->export(),
      'destination' => $this->destination->export(),
      'state' => $this->state
    ];

    $serialized = json_encode($data);
    $this->db->insert($this->destination->guid, [ $this->guid => $serialized ]);
    $this->db->insert($this->owner->guid, [ $this->guid => $serialized ]);
    return $this;
  }

  /**
   * Get the GUID of this boost
   * @return string
   */
  public function getGuid(){
    return $this->guid;
  }

  /**
   * Set the entity to boost
   * @param Entity $entity
   * @return $this
   */
  public function setEntity(Entity $entity){
    $this->entity = $entity;
    return $this;
  }

  /**
   * Get the entity
   * @return Entity
   */
  public function getEntity(){
    return $this->entity;
  }

  /**
   * Destination
   * @param Entity $destination
   * @return $this
   */
  public function setDestination(User $destination){
    $this->destination = $destination;
    return $this;
  }

  /**
   * Get the destination
   * @return Entity
   */
  public function getDestination(){
    return $this->destination;
  }

  /**
   * Set the owner
   * @param Entity $owner
   * @return $this
   */
  public function setOwner(User $owner){
    $this->owner = $owner;
    return $this;
  }

  /**
   * Get the owner
   * @return User
   */
  public function getOwner(){
    return $this->owner;
  }

  /**
   * Return the bid
   * @return int
   */
  public function getBid(){
    return $this->bid;
  }

  /**
   * Set the bid amount
   * @param $bid
   * @return $this
   */
  public function setBid($bid){
    $this->bid = $bid;
    return $this;
  }

  /**
   * Set the state of the boost
   * @param string $state
   * @return $this
   */
  public function setState($state){
    $this->state = $state;
    return $this;
  }

  /**
   * Return the state of the boost
   * @return string
   */
  public function getState(){
    return $this->state;
  }

  public function setTransactionId($id){
    $this->transactionId = $id;
    return $this;
  }

}
