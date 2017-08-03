<?php
/**
 * Created by Marcelo.
 * Date: 03/08/2017
 */

namespace Minds\Core\Wire;


use Minds\Core;
use Minds\Core\Events\Dispatcher;
use Minds\Entities;
use Minds\Entities\User;
use Minds\Helpers;


class Events
{
    public function register()
    {
        Dispatcher::register('subscriptions:cancel', 'all', function ($event) {
            $params = $event->getParameters();
            $user = $params['user'];
            if ($user->type != 'user') {
                return;
            }

            $manager = Core\Di\Di::_()->get('Wire\Manager');

            $wires = $manager->get(['user_guid' => $user->guid, 'type' => 'sent', 'order' => 'DESC']);

            // cancel all wires and subscriptions
            foreach ($wires as $wire) {
                if ($wire->isRecurring() && $wire->isActive()) {
                    if ($wire->getMethod() == 'usd') {
                        // cancel all subscriptions from stripe
                        $this->cancelSubscriptions($user);
                    }
                    $wire->setActive(0)
                        ->save();
                }
            }

        });
    }

    private function cancelSubscriptions(User $user)
    {
        $repo = new Core\Payments\Plans\Repository();
        $repo->setUserGuid($user->guid);
        $subscriptions = $repo->getAllSubscriptions(['wire'], ['limit' => 0]);
        foreach ($subscriptions as $subscription) {
            $repo->setEntityGuid($subscription[0]);
            $plan = $repo->getSubscription('wire');

            $subscription = new Core\Payments\Subscriptions\Subscription()< $subscription->setId($plan->getSubscriptionId())
                ->setMerchant($user);
            $stripe = Core\Di\Di::_()->get('StripePayments');
            $stripe->cancelSubscription($subscription);

            //cancel the plan itself
            $repo->cancel('wire');
        }
    }
}