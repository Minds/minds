<?php
namespace Minds\Core\Rewards;

use Minds\Core\Di\Provider;

class RewardsProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Rewards\Contributions\Repository', function ($di) {
            return new Contributions\Repository();
        }, [ 'useFactory'=> true ]);

        $this->di->bind('Rewards\Contributions\DailyCollection', function ($di) {
            return new Contributions\DailyCollection();
        }, [ 'useFactory'=> false ]);

        $this->di->bind('Rewards\Withdraw\Manager', function ($di) {
            return new Withdraw\Manager();
        }, [ 'useFactory'=> true ]);

        $this->di->bind('Rewards\Withdraw\Repository', function ($di) {
            return new Withdraw\Repository();
        }, [ 'useFactory'=> true ]);

        $this->di->bind('Rewards\ReferralValidator', function ($di) {
            return new ReferralValidator();
        }, [ 'useFactory'=> true ]);

        $this->di->bind('Rewards\JoinedValidator', function ($di) {
            return new JoinedValidator();
        }, [ 'useFactory'=> true ]);

        $this->di->bind('Rewards\OfacBlacklist', function ($di) {
            return new OfacBlacklist();
        }, [ 'useFactory'=> true ]);
    }
}
