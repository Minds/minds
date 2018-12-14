<?php

namespace Minds\Core\VideoChat;

use Minds\Core\Di\Provider as DiProvider;

class Provider extends DiProvider
{
    public function register()
    {
        $this->di->bind('VideoChat\Manager', function ($di) {
            return new Manager();
        }, ['useFactory' => true]);
    }
}