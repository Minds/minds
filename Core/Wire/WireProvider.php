<?php
namespace Minds\Core\Wire;

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
