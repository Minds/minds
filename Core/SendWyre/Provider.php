<?php
/**
 * SendWyre Provider.
 */

namespace Minds\Core\SendWyre;

use Minds\Core\Di\Provider as DiProvider;

class Provider extends DiProvider
{
    public function register()
    {
        $this->di->bind('SendWyre\Repository', function ($di) {
            return new Repository();
        }, ['useFactory' => true]);

        $this->di->bind('SendWyre\Manager', function ($di) {
            return new Manager();
        }, ['useFactory' => true]);
    }
}
