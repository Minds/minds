<?php
/**
 * Minds Notification Provider
 * @author Emi Balbuena
 */

namespace Minds\Core\Notification;

use Minds\Core;
use Minds\Core\Di\Provider;

class NotificationProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Notification\Repository', function ($di) {
            return new Repository();
        });
        $this->di->bind('Notification\Manager', function ($di) {
            return new Manager();
        });
        $this->di->bind('Notification\Batches\Manager', function ($di) {
            return new Batches\Manager();
        });
    }
}
