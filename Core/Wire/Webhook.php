<?php
/**
 * Created by Marcelo.
 * Date: 02/08/2017
 */

namespace Minds\Core\Wire;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Entities;
use Minds\Entities\User;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Payments;
use Minds\Core\Payments\HookInterface;
use Minds\Entities\Wire;
use Minds\Entities\Factory;

class Webhook implements HookInterface
{

    public function onCharged($subscription)
    {
        $planId = $subscription->getPlanId();
        if ($planId == 'wire' || $planId == 'exclusive') {
            $user = $subscription->getCustomer()->getUser();
            /** @var Payments\Stripe\Stripe $stripe */
            $stripe = Di::_()->get('StripePayments');
            $stripePlan = $stripe->getPlan('wire', $user->getMerchant()['id']);

            /** @var Core\Payments\Subscriptions\Repository $repository */
            $repository = Di::_()->get('Payments\Subscriptions\Repository');
            $dbSubscription = $repository->get($subscription->getId());

            /** @var Repository $wireRepository */
            $wireRepository = Di::_()->get('Wire\Repository');
            $entity = Factory::build($dbSubscription->getEntity()->guid);

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

            $wireRepository->setSenderGuid($user->guid)
                ->setWire($wire)
                ->add();

            /** @var Core\Payments\Manager $manager */
            $manager = Di::_()->get('Payments\Manager');
            $manager
                ->setType('wire')
                ->setUserGuid($user->guid)
                ->setTimeCreated(time())
                ->create([
                    'subscription_id' => $subscription->getId(),
                    'payment_method' => 'money',
                    'amount' => $stripePlan->amount,
                    'description' => 'Wire ' . $to  . ' (Recurring)',
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
