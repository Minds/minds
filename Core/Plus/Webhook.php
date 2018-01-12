<?php
/**
 * Minds Wallet Subscription Hook
 */
namespace Minds\Core\Plus;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Payments\HookInterface;
use Minds\Helpers\Wallet as WalletHelper;

class Webhook implements HookInterface
{

    public function onCharged($subscription)
    {
        if ($subscription->getPlanId() == 'plus') {
            $user = $subscription->getCustomer()->getUser();
            WalletHelper::createTransaction($user->guid, 1000, null, "Plus Points");

            /** @var Core\Payments\Manager $manager */
            $manager = Di::_()->get('Payments\Manager');
            $manager
                ->setType('plus')
                ->setUserGuid($user->guid)
                ->setTimeCreated(time())
                ->create([
                    'subscription_id' => $subscription->getId(),
                    'payment_method' => 'money',
                    'amount' => $subscription->getPrice(),
                    'description' => 'Plus',
                    'status' => 'paid'
                ]);
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

    public function onPayoutPaid($payout, $customer, $account)
    {
    }
}
