<?php
namespace Minds\Core\Payments;

use Minds\Core\Events\Dispatcher;
use Minds\Core\Session;
use Minds\Core\Payments;

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
            $export = $event->response() ?: [];
            $currentUser = Session::getLoggedInUserGuid();

            if ($activity->hasExportContext() && $activity->isPaywall() && $params['entity']->owner_guid != $currentUser) {
                $export['message'] = null;
                $export['custom_type'] = null;
                $export['custom_data'] = null;

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
                return $event->setResponse(true);
            }

            if (!$user) {
                return false;
            }

            $repo = new Payments\Plans\Repository();
            $plan = $repo->setEntityGuid($entity->owner_guid)
                ->setUserGuid($user->guid)
                ->getSubscription('exclusive');

            $event->setResponse($plan->getStatus() == 'active');
        });
    }
}
