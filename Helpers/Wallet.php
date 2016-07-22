<?php
/**
 * Wallet Helper functions
 */

namespace Minds\Helpers;

use Minds\Core;
use Minds\Entities;

class Wallet
{
    /**
   * Create a transaction record for the wallet
   */
  public static function createTransaction($user_guid, $points, $entity_guid = null, $description = "")
  {
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

  /**
   * Log purchased purchased_points
   */
  public static function logPurchasedPoints($user_guid, $points)
  {
      $db = new Core\Data\Call('entities_by_time');
      $db->insert("purchased_points", [
        $user_guid => json_encode([
          'points' => $points,
          'ts' => time()
        ])
      ]);
  }
}
