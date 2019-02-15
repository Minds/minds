<?php
/**
 * Minds Subscriptions Provider
 */

namespace Minds\Core\Subscriptions;

use Minds\Core\Di\Provider as DiProvider;

class Provider extends DiProvider
{
    public function register()
    {
        $this->di->bind('Subscriptions\Manager', function ($di) {
            return new Manager();
        }, [ 'useFactory'=>false ]);
    }
}
