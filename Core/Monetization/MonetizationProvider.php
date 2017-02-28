<?php
/**
 * Minds Monetization Provider
 */

namespace Minds\Core\Monetization;

use Minds\Core\Di\Provider;

class MonetizationProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Monetization\Admin', function ($di) {
            return new Admin();
        }, [ 'useFactory' => true ]);

        $this->di->bind('Monetization\Ads', function ($di) {
            return new Ads();
        }, [ 'useFactory' => true ]);

        $this->di->bind('Monetization\Manager', function ($di) {
            return new Manager();
        }, [ 'useFactory' => true ]);

        $this->di->bind('Monetization\Merchants', function ($di) {
            return new Merchants();
        }, [ 'useFactory' => true ]);

        $this->di->bind('Monetization\Payouts', function ($di) {
            return new Payouts();
        }, [ 'useFactory' => true ]);

        $this->di->bind('Monetization\Users', function ($di) {
            return new Users();
        }, [ 'useFactory' => true ]);

        $this->di->bind('Monetization\ServiceCache', function ($di) {
            return new ServiceCache();
        }, [ 'useFactory' => true ]);

        /* Services */
        $this->di->bind('Monetization\Services\Adsense', function ($di) {
            return new Services\AdsensePolyfill();
        }, [ 'useFactory' => true ]);

        /* Default service */
        $this->di->bind('Monetization\DefaultService', function ($di) {
            return $di->get('Monetization\Services\Adsense');
        }, [ 'useFactory' => true ]);
    }
}
