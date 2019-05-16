<?php
namespace Minds\Core\Reports;

use Minds\Core\Di\Provider as DiProvider;

class Provider extends DiProvider
{
    public function register()
    {
        $this->di->bind('Reports\Actions', function ($di) {
            return new Actions();
        }, [ 'useFactory'=> true ]);

        $this->di->bind('Reports\Appeals', function ($di) {
            return new Appeals();
        }, [ 'useFactory'=> true ]);

        $this->di->bind('Reports\Repository', function ($di) {
            return new Repository();
        }, [ 'useFactory'=> true ]);

        // New moderation system
        $this->di->bind('Moderation\Manager', function ($di) {
            return new Manager();
        }, [ 'useFactory'=> false ]);
        $this->di->bind('Moderation\Appeals\Manager', function ($di) {
            return new Appeals\Manager();
        }, [ 'useFactory'=> false ]);
        $this->di->bind('Moderation\UserReports\Manager', function ($di) {
            return new UserReports\Manager();
        }, [ 'useFactory'=> false ]);
        $this->di->bind('Moderation\Jury\Manager', function ($di) {
            return new Jury\Manager();
        }, [ 'useFactory'=> false ]);
        $this->di->bind('Moderation\Verdict\Manager', function ($di) {
            return new Verdict\Manager();
        }, [ 'useFactory'=> false ]);
        $this->di->bind('Moderation\Stats\Manager', function ($di) {
            return new Stats\Manager;
        }, [ 'useFactory'=> true ]);
        $this->di->bind('Moderation\Strikes\Manager', function ($di) {
            return new Strikes\Manager;
        }, [ 'useFactory'=> true ]);

        $this->di->bind('Moderation\Summons\Manager', function ($di) {
            return new Summons\Manager();
        }, [ 'useFactory'=> true ]);
    }
}
