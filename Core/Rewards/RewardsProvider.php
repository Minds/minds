<?php
namespace Minds\Core\Rewards;

use Minds\Core\Di\Provider;

class RewardsProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Rewards\Repository', function ($di) {
            return new Repository();
        }, [ 'useFactory'=> true ]);

        $this->di->bind('Rewards\Contributions\Repository', function ($di) {
            return new Contributions\Repository();
        }, [ 'useFactory'=> true ]);

        $this->di->bind('Rewards\Balance', function ($di) {
            return new Balance();
        }, [ 'useFactory'=> false ]);
    }
}
