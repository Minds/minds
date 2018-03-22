<?php
/**
 * Minds Plugins Provider
 */

namespace Minds\Core\ThirdPartyNetworks;

use Minds\Core\Di\Provider;

class ThirdPartyNetworksProvider extends Provider
{
    public function register()
    {
        $this->di->bind('ThirdPartyNetworks\Manager', function ($di) {
            return new Manager();
        }, ['useFactory' => true]);

        $this->di->bind('ThirdPartyNetworks\Credentials', function ($di) {
            return new Credentials();
        }, ['useFactory' => true]);

        $this->di->bind('ThirdPartyNetworks\Facebook\Manager', function ($di) {
            return new Facebook\Manager();
        }, ['useFactory' => false]);
    }
}
