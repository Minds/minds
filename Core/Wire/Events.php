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

        Dispatcher::register('recurring-subscriptions:process', 'wire', function (Core\Events\Event $event) {
            $params = $event->getParameters();
            $recurringSubscription = $params['recurring_subscription'];

            $user = Entities\Factory::build($recurringSubscription['entity_guid']);
            $sender = new User($recurringSubscription['user_guid']);

            /** @var Tokens $wireMethod */
            $wireMethod = Methods\Factory::build($recurringSubscription['payment_method']);

            $onRecurring = $wireMethod
                ->setRecurring(true)
                ->setAmount($recurringSubscription['amount'])
                ->setActor($sender)
                ->setEntity($user)
                ->setTimestamp(time())
                ->onRecurring($recurringSubscription['subscription_id']);

            return $event->setResponse($onRecurring);
        });
    }

    private function cancelSubscriptions(User $user)
    {
        /** @var Core\Payments\Subscriptions\Manager $manager */
        $manager = Di::_()->get('Payments\Subscriptions\Manager');
        $manager
            ->setUserGuid($user->guid)
            ->setType('wire');

        $subscriptions = $manager->fetch([ 'type' => 'wire', 'limit' => null, 'hydrate' => false ]);

        foreach ($subscriptions as $subscription) {
            if (!$subscription['subscription_id']) {
                continue;
            }

            $manager
                ->setPaymentMethod($subscription['payment_method'])
                ->setEntityGuid($subscription['entity_guid']);

            $subscription = new Core\Payments\Subscriptions\Subscription();
            $subscription
                ->setId($subscription['subscription_id'])
                ->setMerchant($user);

            $stripe = Core\Di\Di::_()->get('StripePayments');
            $stripe->cancelSubscription($subscription);

            //cancel the plan itself
            $manager->cancel();
        }
    }
}
