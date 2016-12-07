<?php
namespace Minds\Core\Payments;

use Minds\Core\Events\Dispatcher;
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
            $export = $event->response() ?: [];
            $currentUser = Session::getLoggedInUserGuid();

            if ($activity->hasExportContext() && $activity->isPaywall() && $params['entity']->owner_guid != $currentUser) {
                $export['message'] = null;
                $export['custom_data'] = null;

                return $event->setResponse($export);
            }

            if (!$currentUser) {
                return;
            }

        });
    }
}
