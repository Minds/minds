<?php
/**
 * Created by Marcelo.
 * Date: 02/08/2017
 */

namespace Minds\Core\Wire;

use Minds\Core\Di\Di;
use Minds\Core\Entities;
use Minds\Core\Payments\HookInterface;
use Minds\Core\Payments;
use Minds\Entities\Wire;

class Webhook implements HookInterface
{

    public function onCharged($subscription)
    {
        if ($subscription->getPlanId() == 'wire') {
            $user = $subscription->getCustomer()->getUser();
            $stripe = Di::_()->get('StripePayments');
            $stripePlan = $stripe->getPlan('wire', $user->getMerchant()['id']);

            $planRepo = new Payments\Plans\Repository();
            $plan = $planRepo->setEntityGuid(0)
                ->setUserGuid($user->guid)
                ->getSubscription('wire');

            $repo = Di::_()->get('Wire\Repository');
            $entity = Entities::get($plan->getEntityGuid())[0];

            $wire = new Wire();
            $wire->setMethod('usd')
                ->setEntity($entity)
                ->setTo($entity->ownerObj->guid)
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

        $plus = new Subscription();
        $plus->setUser($user);
        $plus->cancel();

        // TODO get wire entity and mark it as inactive
    }
}
