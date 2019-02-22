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
    }
}
