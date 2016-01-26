<?php
/**
 * Minds Wallet Subscription Hook
 */
namespace Minds\Core\Wallet;

use Minds\Core\Payments\Subscriptions;
use Minds\Helpers\Wallet as WalletHelper;

class PointsSubscription implements Subscriptions\HookInterface
{

    public function onCharged($subscription)
    {
        WalletHelper::createTransaction(Core\Session::getLoggedinUser()->guid, $points, null, "purchase");
    }

    public function onActive($subscription)
    {

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
