<?php
/**
 * Minds Events Provider
 */

namespace Minds\Core\Events;

use Minds\Core\Di\Provider as DiProvider;

class Provider extends DiProvider
{
    public function register()
    {
        $this->di->bind('EventsDispatcher', function ($di) {
            return new EventsDispatcher();
        }, ['useFactory' => true]);
    }
}
