<?php

/**
 * Minds Features Provider
 *
 * @author emi
 */

namespace Minds\Core\Features;

use Minds\Core\Di\Provider;

class FeaturesProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Features', function ($di) {
            return new Manager();
        }, [ 'useFactory'=> true ]);
    }
}
