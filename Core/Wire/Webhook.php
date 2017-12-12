<?php
/**
 * Created by Marcelo.
 * Date: 02/08/2017
 */

namespace Minds\Core\Wire;

use Minds\Core\Di\Di;
use Minds\Core\Entities;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Payments;
use Minds\Core\Payments\HookInterface;
use Minds\Entities\Wire;
use Minds\Entities\User;

class Webhook implements HookInterface
{

    public function onCharged($subscription)
    {
        $planId = $subscription->getPlanId();
        if ($planId == 'wire' || $planId == 'exclusive') {
            $user = $subscription->getCustomer()->getUser();
            $stripe = Di::_()->get('StripePayments');
            $stripePlan = $stripe->getPlan('wire', $user->getMerchant()['id']);

            $planRepo = new Payments\Plans\Repository();
            $plan = $planRepo->setEntityGuid(0)
                ->setUserGuid($user->guid)
                ->getSubscriptionById($subscription->getId());

            $repo = Di::_()->get('Wire\Repository');
            $entity = Entities::get($plan->getEntityGuid())[0];

            $to = "";

            if ($entity instanceof User) {
                $to = $entity->guid;
            } else {
                $to = $entity->ownerObj->guid;
            }

            $wire = new Wire();
            $wire->setMethod('money')
                ->setEntity($entity)
                ->setTo($to)
                ->setFrom($user->guid)
                ->setTimeCreated(time())
                ->setAmount($stripePlan->amount)
                ->setRecurring(true);

            $repo->setSenderGuid($user->guid)
                ->setWire($wire)
                ->add();
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

        //$plus = new Subscription();
        //$plus->setUser($user);
        //$plus->cancel();

        // TODO get wire entity and mark it as inactive
    }

    public function onPayoutPaid($payout, $customer, $account)
    {
        $accountString = $account->bank_name . ' ' . '****' . $account->last4 . ' / ' . $account->routing_number;

        Dispatcher::trigger('wire-payment-email', 'object', [
            'charged' => true,
            'dateOfDispatch' => time(),
            'bankAccount' => $accountString,
            'user' => $customer->getUser(),
        ]);
    }
}
