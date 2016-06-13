<?php
/**
 * Minds Plugins Provider
 */

namespace Minds\Core\Plugins;

use Minds\Core\Di\Provider;

class PluginsProvider extends Provider
{

    public function register()
    {
        $this->di->bind('Plugins\Manager', function($di){
            return new Manager();
        }, ['useFactory'=>true]);
    }

}
