<?php
/**
 * Minds Wallet Subscription Hook
 */
namespace Minds\Core\Plus;

use Minds\Core\Payments\HookInterface;
use Minds\Helpers\Wallet as WalletHelper;

class Webhook implements HookInterface
{

    public function onCharged($subscription)
    {
        if ($subscription->getPlanId() == 'plus') {
            $user = $subscription->getCustomer()->getUser();
            WalletHelper::createTransaction($user->guid, 1000, null, "Plus Points");
        }
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
        error_log("[webhook]:: canceled");
        $user = $subscription->getCustomer()->getUser();

        $plus = new Subscription();
        $plus->setUser($user);
        $plus->cancel();

        $user->plus = 0;
        $user->save();
    }
}
