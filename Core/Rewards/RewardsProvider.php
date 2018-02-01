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

        $this->di->bind('Rewards\Withdraw\Manager', function ($di) {
            return new Withdraw\Manager();
        }, [ 'useFactory'=> true ]);

        $this->di->bind('Rewards\Withdraw\Repository', function ($di) {
            return new Withdraw\Repository();
        }, [ 'useFactory'=> true ]);
    }
}
