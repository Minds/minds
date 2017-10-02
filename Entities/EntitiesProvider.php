<?php

namespace Minds\Entities;

use Minds\Core\Di\Provider;
use Minds\Core\Entities;

class EntitiesProvider extends Provider
{
    /**
     * Registers providers onto DI
     * @return void
     */
    public function register()
    {
        $this->di->bind('Entities', function ($di) {
            return new Entities();
        }, ['useFactory' => true]);
        $this->di->bind('Entities\Factory', function ($di) {
            return new Factory();
        }, ['useFactory' => true]);
    }
}
