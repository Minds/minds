<?php

namespace Minds\Core\Permissions;

use Minds\Core\Di\Provider as DiProvider;

class Provider extends DiProvider
{
    public function register()
    {
        $this->di->bind('Permissions\Manager', function ($di) {
            return new Manager();
        });
    }
}
