<?php
/**
 * Minds Programs Provider
 */

namespace Minds\Core\Programs;

use Minds\Core\Di\Provider;

class ProgramsProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Programs\Manager', function ($di) {
            return new Manager();
        }, [ 'useFactory' => true ]);

        $this->di->bind('Programs\Admin', function ($di) {
            return new Admin();
        }, [ 'useFactory' => true ]);
    }
}
