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
            $currentUser = Session::getLoggedInUserGuid();

            if (!$currentUser) {
                return;
            }

            if ($params['entity']->hasExportContext() && $params['entity']->isPaywall() && $params['entity']->owner_guid == $currentUser)
            {
                $export = $event->response() ?: [];

                $params['entity']->setPaywall(false);
                $export['paywall'] = 0; // @todo: false doesn't work
                $export['isMonetized'] = true;

                $event->setResponse($export);
            }
        });
    }
}
