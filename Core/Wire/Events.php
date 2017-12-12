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

            switch ($recurringSubscription['payment_method']) {
                case 'tokens':

                    $user = Entities\Factory::build($recurringSubscription['entity_guid']);
                    $sender = new User($recurringSubscription['user_guid']);

                    /** @var Tokens $wireMethod */
                    $wireMethod = Di::_()->get('Wire\Method\Tokens');
                    $wireMethod
                        ->setAmount($recurringSubscription['amount'])
                        ->setEntity($user)
                        ->setTimestamp(time())
                        ->chargeRecurringAndCreate($user, $sender);

                    return $event->setResponse(true);
                default:
                    return $event->setResponse(false);
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
