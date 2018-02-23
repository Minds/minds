<?php

namespace Minds\Core\Email;

use Minds\Core\Di\Provider;

class EmailProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Email\Manager', function ($di) {
            return new Manager();
        }, ['useFactory' => true]);

        $this->di->bind('Email\Repository', function ($di) {
            return new Repository();
        }, ['useFactory' => true]);
    }
}