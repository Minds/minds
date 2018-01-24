<?php
/**
 * Created by Marcelo.
 * Date: 03/08/2017
 */

namespace Minds\Core\Wire;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Wire\Methods\Tokens;
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

        /**
         * Legcacy compatability for exclusive content
         */
        Dispatcher::register('export:extender', 'activity', function($event) {
            $params = $event->getParameters();
            $activity = $params['entity'];
            if($activity->type != 'activity'){
                return;
            }
            $export = $event->response() ?: [];
            $currentUser = Session::getLoggedInUserGuid();

            if ($activity->isPaywall() && !$activity->getWireThreshold()) {
                $export['wire_threshold'] = [
                  'type' => 'money',
                  'min' => $activity->getOwnerEntity()->getMerchant()['exclusive']['amount']
                ];
                return $event->setResponse($export);
            }
        });

        // Recurring subscriptions

        Dispatcher::register('subscriptions:process', 'wire', function (Core\Events\Event $event) {
            $params = $event->getParameters();
            /** @var Core\Payments\Subscriptions\Subscription $subscription */
            $subscription = $params['subscription'];

            $user = Entities\Factory::build($subscription->getEntity()->guid);
            $sender = new User($subscription->getUser()->guid);

            /** @var Tokens $wireMethod */
            $wireMethod = Methods\Factory::build($subscription->getPaymentMethod());

            $onRecurring = $wireMethod
                ->setRecurring(true)
                ->setAmount($subscription->getAmount())
                ->setActor($sender)
                ->setEntity($user)
                ->setTimestamp(time())
                ->onRecurring($subscription->getId());

            return $event->setResponse($onRecurring);
        });
    }

    private function cancelSubscriptions(User $user)
    {
        /** @var Core\Payments\Subscriptions\Repository $repository */
        $repository = Di::_()->get('Payments\Subscriptions\Repository');
        /** @var Core\Payments\Subscriptions\Manager $manager */
        $manager = Di::_()->get('Payments\Subscriptions\Manager');

        $subscriptions = $repository->getList([
            'user_guid' => $user->guid,
            'plan_id' => 'wire'
        ]);

        foreach ($subscriptions as $subscription) {
            if (!$subscription->getId()) {
                continue;
            }

            $subscription->setMerchant($user);

            $stripe = Core\Di\Di::_()->get('StripePayments');
            $stripe->cancelSubscription($subscription);

            //cancel the plan itself
            $manager->setSubscription($subscription);
            $manager->cancel();
        }
    }
}
