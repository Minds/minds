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
        }, ['useFactory'=>true]);

        $this->di->bind('Wire\Manager', function ($di) {
            return new Manager(Di::_()->get('Database\Cassandra\Cql'));
        }, ['useFactory' => true]);

        $this->di->bind('Wire\Repository', function ($di) {
            return new Repository(Di::_()->get('Database\Cassandra\Cql'), Di::_()->get('Config'));
        }, ['useFactory'=>false]);

        $this->di->bind('Wire\Counter', function ($di) {
            return new Counter;
        }, ['useFactory'=>true]);

        $this->di->bind('Wire\Thresholds', function ($di) {
            return new Thresholds();
        }, ['useFactory'=>true]);

        $this->di->bind('Wire\Method\Points', function ($di) {
            return new Methods\Points();
        }, ['useFactory'=>false]);

        $this->di->bind('Wire\Method\Money', function ($di) {
            return new Methods\Money($di->get('StripePayments'));
        }, ['useFactory'=>false]);

        $this->di->bind('Wire\Method\Bitcoin', function ($di) {
        }, ['useFactory'=>false]);
    }
}
