<?php
namespace Minds\Core\Payments;

use Minds\Core\Email\Campaigns;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Payments;
use Minds\Core\Session;

/**
 * Minds Payments Events
 */
class Events
{
    public function register()
    {
        Dispatcher::register('export:extender', 'activity', function($event) {
            $params = $event->getParameters();
            $activity = $params['entity'];
            if($activity->type != 'activity'){
                return;
            }
            $export = $event->response() ?: [];
            $currentUser = Session::getLoggedInUserGuid();

            if ($activity->isPaywall() && $params['entity']->owner_guid != $currentUser) {
                $export['message'] = null;
                $export['custom_type'] = null;
                $export['custom_data'] = null;
                $export['thumbnail_src'] = null;
                $export['perma_url'] = null;
                $export['blurb'] = null;
                $export['title'] = null;

                return $event->setResponse($export);
            }

            if (!$currentUser) {
                return;
            }

        });

        Dispatcher::register('acl:read', 'object', function($event) {
            $params = $event->getParameters();
            $entity = $params['entity'];
            $user = $params['user'];

            if (!method_exists($entity, 'getFlag') || !$entity->getFlag('paywall')) {
                return;
            }

            if (!$user) {
                return false;
            }

            //Plus hack
            if ($entity->owner_guid == '730071191229833224') {
                //Check if a plus subscription exists
                $repo = new Payments\Plans\Repository();
                $plan = $repo->setEntityGuid(0)
                    ->setUserGuid($user->guid)
                    ->getSubscription('plus');

                if ($plan->getStatus() == 'active') {
                    return $event->setResponse(true);
                }
            }

            $repo = new Payments\Plans\Repository();
            $plan = $repo->setEntityGuid($entity->owner_guid)
                ->setUserGuid($user->guid)
                ->getSubscription('exclusive');

            return $event->setResponse($plan->getStatus() == 'active');
        });

        Dispatcher::register('wire-payment-email', 'object', function ($event) {
            $campaign = new Campaigns\WirePayment;
            $params = $event->getParameters();
            $user = $params['user'];
            if (!$user) {
                return false;
            }

            $campaign->setUser($user);

            if ($params['charged']) {
                $bankAccount = $params['bankAccount'];
                $dateOfDispatch = $params['dateOfDispatch'];
                if (!$bankAccount || !$dateOfDispatch) {
                    return false;
                }
                $campaign->setBankAccount($bankAccount)
                    ->setDateOfDispatch($dateOfDispatch);
            } else {
                $amount = $params['amount'];
                $unit = $params['unit'];
                if (!$amount || !$unit) {
                    return false;
                }

                $campaign->setAmount($amount)
                    ->setDescription($unit);
            }

            $campaign->send();


            return $event->setResponse(true);
        });
    }
}
