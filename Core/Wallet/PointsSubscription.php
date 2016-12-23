<?php
/**
 * Minds Wallet Subscription Hook
 */
namespace Minds\Core\Wallet;

use Minds\Core;
use Minds\Core\Payments\HookInterface;
use Minds\Helpers\Wallet as WalletHelper;
use Minds\Entities;

class PointsSubscription implements HookInterface
{
    private $rate = 0.001;

    public function onCharged($subscription)
    {
        error_log("[webhook]:: " .  print_r($subscription, true));
        $db = new Core\Data\Call('user_index_to_guid');

        //find the customer
        $user_guids = $db->getRow("subscription:" . $subscription->getId());
        $user = Entities\Factory::build($user_guids[0]);

        error_log("[webhook]:: got onCharge");
        WalletHelper::createTransaction($user->guid, ($subscription->getPrice() / $this->rate) * 1.1, null, "Purchase (Recurring)");
    }

    public function onActive($subscription)
    {
        error_log("[webhook]:: gotOnActive");
    }

    public function onExpired($subscription)
    {
    }

    public function onOverdue($subscription)
    {
    }

    public function onCanceled($subscription)
    {
    }
}
