<?php
namespace Minds\Core\Boost;

use Minds\Core;
use Minds\Core\Di\Di;
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
       Dispatcher::register('boost:completed', 'boost', function ($event) {
            $campaign = new Campaigns\WhenBoost();
            $params = $event->getParameters();
            $boost = $params['boost'];

            $campaign->setUser($boost->getOwner())
                ->setBoost($boost);

            $campaign->send();

            return $event->setResponse(true);
        });
    }
}
