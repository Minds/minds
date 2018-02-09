<?php

namespace Minds\Core\Wire;

use Minds\Core\Di\Di;
use Minds\Core\Di\Provider;

/**
 * Wire Providers
 */
class WireProvider extends Provider
{
    /**
     * Registers providers onto DI
     * @return null
     */
    public function register()
    {
        $this->di->bind('Wire', function ($di) {
        }, ['useFactory' => true]);

        $this->di->bind('Wire\Manager', function ($di) {
            return new Manager();
        }, ['useFactory' => true]);

        $this->di->bind('Wire\Subscriptions\Manager', function ($di) {
            return new \Minds\Core\Wire\Subscriptions\Manager();
        }, ['useFactory' => true]);

        $this->di->bind('Wire\Repository', function ($di) {
            return new Repository(Di::_()->get('Database\Cassandra\Cql'), Di::_()->get('Config'));
        }, ['useFactory' => false]);

        $this->di->bind('Wire\Counter', function ($di) {
            return new Counter;
        }, ['useFactory' => true]);

        $this->di->bind('Wire\Thresholds', function ($di) {
            return new Thresholds();
        }, ['useFactory' => true]);

        $this->di->bind('Wire\Sums', function ($di) {
            return new Sums();
        }, ['useFactory' => false]);
    }
}
