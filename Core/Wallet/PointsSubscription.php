<?php
/**
 * Minds Wallet Subscription Hook
 */
namespace Minds\Core\Wallet;

use Minds\Core\Payments\HookInterface;
use Minds\Helpers\Wallet as WalletHelper;

class PointsSubscription implements HookInterface
{

    public function onCharged($subscription)
    {
        error_log("[webhook]:: got onCharge");
        WalletHelper::createTransaction(Core\Session::getLoggedinUser()->guid, $points, null, "purchase");
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
