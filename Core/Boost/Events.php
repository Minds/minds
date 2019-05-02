<?php

namespace Minds\Core\Boost;

use Minds\Core\Email\Campaigns;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Payments;

/**
 * Minds Payments Events.
 */
class Events
{
    public function register()
    {
        Dispatcher::register('boost:completed', 'boost', function ($event) {
            $campaign = new Campaigns\WhenBoost();
            $params = $event->getParameters();
            $boost = $params['boost'];
            $campaign->setUser($boost->getOwner())
                ->setBoost($boost->export());
            $campaign->send();

            return $event->setResponse(true);
        });
    }
}
