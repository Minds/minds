<?php
/**
 * Relationships key => column store for Cassandra
 */
namespace Minds\Core\Data;

class Relationships {

  private $db = NULL;

  public function __construct($db = NULL){
    if($db){
      $this->db = new $db;
    } else {
      $this->db = new Call('relationships');
    }
  }

  /**
   * Create a relationship
   * @param string $guid_one
   * @param string $relationship
   * @param string $guid_two
   * @return bool
   */
  public function create($guid_one, $relationship, $guid_two){

    if(!$guid_one)
      throw new \Exception("\$guid_one must be provided");
    if(!$relationship)
      throw new \Exception("\$relationship must be provided");
    if(!$guid_two)
      throw new \Exception("\$guid_two must be provided");

    if(!$this->db->insert($guid_one . ':' . $relationship, array($guid_two=>time())))
      return false;

    if(!$this->db->insert($guid_two . ':' . $relationship . ':inverted', array($guid_one=>time())))
  		return false;

  	return true;
  }

  /**
   * Remove a relationship
   * @param string $guid_one
   * @param string $relationship
   * @param string $guid_two
   * @return bool
   */
  public function remove($guid_one, $relationship, $guid_two){

    if(!$guid_one)
      throw new \Exception("\$guid_one must be provided");
    if(!$relationship)
      throw new \Exception("\$relationship must be provided");
    if(!$guid_two)
      throw new \Exception("\$guid_two must be provided");

    if($db->removeAttributes($guid_one . ':' . $relationship, array($guid_two)) === false)
      return false;

    if($db->removeAttributes($guid_two . ':' . $relationship . ':inverted', array($guid_one)) === false)
      return false;

    return true;
  }

  /**
   * Check if a relationship exists
   * @param string $guid_one
   * @param string $relationship
   * @param string $guid_two
   * @return bool
   */
  public function check($guid_one, $relationship, $guid_two){

    if(!$guid_one)
      throw new \Exception("\$guid_one must be provided");
    if(!$relationship)
      throw new \Exception("\$relationship must be provided");
    if(!$guid_two)
      throw new \Exception("\$guid_two must be provided");


    /**
     * Checks for the relationship, if not found checks if it was created in the inverted
     */
    $result = $this->db->getRow($guid_one . ':' . $relationship, array('offset'=>$guid_two, 'limit'=>1));

  	if(isset($result[$guid_two])){
  		return true;
  	}

  	$result = $this->db->getRow($guid_two . ':' . $relationship . ':inverted', array('offset'=>$guid_one, 'limit'=>1));
  	if(isset($result[$guid_one])){
      return true;
    }

  	return false;

  }

}
