<?php
/**
 * Wallet Helper functions
 */

namespace Minds\Helpers;

use Minds\Entities;

class Wallet{

  /**
   * Create a transaction record for the wallet
   */
  public static function createTransaction($user_guid, $points, $entity_guid = NULL, $description = ""){
    $transaction = new Entities\Object\Points_transaction();
    $transaction->setPoints($points)
        ->setOwnerGuid($user_guid)
        ->setDescription($description)
        ->setEntityGuid($entity_guid)
        ->save();
    /**
     * Update the userscount
     */
    Counters::increment($user_guid, 'points', $points);
  }

}
