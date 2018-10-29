<?php
/**
 * Minds Sessions Provider
 */
namespace Minds\Core\Sessions;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Di\Provider;

class SessionsProvider extends Provider
{

    public function register()
    {
        $this->di->bind('Sessions\Manager', function ($di) {
            return new Manager;
        }, ['useFactory'=>true]);
    }

}
