<?php
namespace Minds\Helpers;

use Minds\Core;
use Minds\Entities;

/**
 * Helper for Wallet operations
 * @todo Avoid static and use proper DI
 */
class Wallet
{
    /**
     * Creates a new transaction for a user
     * @param  mixed  $user_guid
     * @param  int    $points
     * @param  mixed  $entity_guid
     * @param  string $description
     * @return null
     */
    public static function createTransaction($user_guid, $points, $entity_guid = null, $description = "")
    {
        $transaction = new Entities\Object\Points_transaction();
        $transaction->setPoints($points)
            ->setOwnerGuid($user_guid)
            ->setDescription($description)
            ->setEntityGuid($entity_guid)
            ->save();

        Counters::increment($user_guid, 'points', $points);
    }

    /**
     * Log purchased points operation
     * @param  mixed $user_guid
     * @param  int   $points
     * @return null
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
