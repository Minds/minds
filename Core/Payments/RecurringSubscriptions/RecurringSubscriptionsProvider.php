<?php

/**
 * Minds Recurring Subscriptions Provider
 *
 * @author emi
 */

namespace Minds\Core\Payments\RecurringSubscriptions;

use Minds\Core\Di\Provider;

class RecurringSubscriptionsProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Payments\RecurringSubscriptions\Manager', function ($di) {
            return new Manager();
        });

        $this->di->bind('Payments\RecurringSubscriptions\Queue', function ($di) {
            return new Queue();
        }, [ 'useFactory' => true ]);

        $this->di->bind('Payments\RecurringSubscriptions\Repository', function ($di) {
            return new Repository();
        }, [ 'useFactory' => true ]);
    }
}
