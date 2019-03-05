<?php

/**
 * Issues Provider
 *
 * @author Maritn Santangelo
 */

namespace Minds\Core\Issues;

use Minds\Core\Di\Provider;

class IssuesProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Issues\Service\Gitlab', function () {
            return new Services\Gitlab();
        });
        $this->di->bind('Issues\Manager', function () {
            return new Manager();
        });


    }
}
