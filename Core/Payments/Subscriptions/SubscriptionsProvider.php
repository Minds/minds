<?php

/**
 * Minds Subscriptions Provider
 *
 * @author emi / mark
 */

namespace Minds\Core\Payments\Subscriptions;

use Minds\Core\Di\Provider;

class SubscriptionsProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Payments\Subscriptions\Manager', function ($di) {
            return new Manager();
        });

        $this->di->bind('Payments\Subscriptions\Queue', function ($di) {
            return new Queue();
        }, [ 'useFactory' => true ]);

        $this->di->bind('Payments\Subscriptions\Repository', function ($di) {
            return new Repository();
        }, [ 'useFactory' => true ]);
    }
}
