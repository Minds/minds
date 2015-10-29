<?php

namespace Minds\Core\Boost;

use Minds\Core;
use Minds\Core\Data;
use Minds\Interfaces;
use Minds\Entities;
use Minds\Helpers;
use Minds\Core\Payments;

/**
 * Pro boost handler
 */
class Peer implements Interfaces\BoostHandlerInterface{

    private $guid;

    public function __construct($options){
      if(isset($options['destination'])){
      	$this->guid = $options['destination'];
      }
    }

   /**
     * Boost an entity
     * @param object/int $entity - the entity to boost
     * @param int $points
     * @return boolean
     */
    public function boost($entity, $points){
      return null;
    }

     /**
     * Return all pro boosts
     * @param int $limit
     * @param string $offset
     * @return array
     */
    public function getReviewQueue($limit, $offset = ""){
        $db = new Data\Call('entities_by_time');
        $data = $db->getRow("boost:peer:$this->guid", ['limit'=>$limit, 'offset'=>$offset, 'reversed'=>true]);

        $boosts = [];
        foreach($data as $guid => $raw_data){
          //$raw_data['guid']
          $boosts[] = (new Entities\Boost\Peer())
            ->loadFromArray(json_decode($raw_data, true));
        }
        return $boosts;
    }

    /**
     * Accept a boost and do a remind
     * @param object/int $entity
     * @param int points
     * @return boolean
     */
    public function accept($_id, $impressions){

      $db = new Data\Call('entities_by_time');
      $data = $db->getRow("boost:peer:$this->guid", ['limit'=>1, 'offset'=>$_id ]);
      if(key($data) != $_id)
        return false;

      $boost = (new Entities\Boost\Peer($db))
        ->loadFromArray(json_decode($data[$_id], true));

      if($boost->getType() == "pro"){
        //we need to void the transaction
        try{
          Payments\Factory::build('braintree')->chargeSale((new Payments\Sale)->setId($boost->getTransactionId()));
        } catch(\Exception $e){
          var_dump($e->getMessage()); exit;
          return false;
        }
      } else {
        Helpers\Wallet::createTransaction($boost->getDestination()->guid, $boost->getBid(), $boost->getGuid(), "Peer Boost");
      }

      $boost->setState('accepted')
        ->save();

      return true;
    }

    /**
     * Reject a boost
     * @param object/int $entity
     * @return boolean
     */
    public function reject($boost, $impressions){
      $db = new Data\Call('entities_by_time');
      $data = $db->getRow("boost:peer:$this->guid", ['limit'=>1, 'offset'=>$_id ]);
      if(key($data) != $_id)
        return false;

      $boost = (new Entities\Boost\Peer($db))
        ->loadFromArray(json_decode($data[$_id], true));

      if($boost->getType() == "pro"){
        //we need to void the transaction
        try{
          Payments\Factory::build('braintree')->voidSale((new Payments\Sale)->setId($boost->getTransactionId()));
        } catch(\Exception $e){
          return false;
        }
      } else {
        Helpers\Wallet::createTransaction($boost->getOwner()->guid, $boost->getBid(), $boost->getGuid(), "Rejected boost");
      }

      $boost->setState('rejected')
        ->save();

      return true;
    }

    /**
     * Return a boost
     * @return array
     */
    public function getBoost($offset = ""){

       ///
       //// THIS DOES NOT APPLY BECAUSE IT'S PRE-AGREED
       ///

    }

}
