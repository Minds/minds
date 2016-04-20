<?php
/**
 * Minds Events Provider
 */

namespace Minds\Core\Events;

use Minds\Core\Di\Provider;

class EventsProvider extends Provider
{

    public function register()
    {
        $this->di->bind('EventsDispatcher', function($di){
            return new Dispatcher();
        }, ['useFactory'=>true]);
    }

}
