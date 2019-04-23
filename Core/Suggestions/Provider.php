<?php
/**
 * Minds Suggestion Provider.
 */

namespace Minds\Core\Suggestions;

use Minds\Core\Di\Provider as DiProvider;

class Provider extends DiProvider
{
    public function register()
    {
        $this->di->bind('Suggestions\Manager', function ($di) {
            return new Manager();
        }, ['useFactory' => false]);

        $this->di->bind('Suggestions\Repository', function ($di) {
            return new Repository();
        }, ['useFactory' => true]);

        $this->di->bind('Suggestions\Pass\Manager', function ($di) {
            return new Pass\Manager();
        }, ['useFactory' => true]);

        $this->di->bind('Suggestions\Pass\Repository', function ($di) {
            return new Pass\Repository();
        }, ['useFactory' => true]);
    }
}
