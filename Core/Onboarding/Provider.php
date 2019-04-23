<?php
/**
 * Minds Onboarding Provider.
 */

namespace Minds\Core\Onboarding;

use Minds\Core\Di\Provider as DiProvider;

class Provider extends DiProvider
{
    public function register()
    {
        $this->di->bind('Onboarding\Manager', function ($di) {
            return new Manager();
        }, ['useFactory' => false]);
    }
}
