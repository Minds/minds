<?php
/**
 * Minds Steward Provider.
 */

namespace Minds\Core\Steward;

use Minds\Core\Di\Provider as DiProvider;

class Provider extends DiProvider
{
    public function register()
    {
        $this->di->bind('Steward\AutoReporter', function ($di) {
            return new AutoReporter();
        }, ['useFactory' => false]);
    }
}
