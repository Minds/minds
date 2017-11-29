<?php
/**
 * Minds SMS Provider
 */

namespace Minds\Core\SMS;

use Minds\Core\Di\Provider;

class SMSProvider extends Provider
{
    public function register()
    {
        $this->di->bind('SMS', function ($di) {
            return new Service();
        }, ['useFactory' => true]);
    }
}