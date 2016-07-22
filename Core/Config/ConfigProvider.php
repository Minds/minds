<?php
/**
 * Minds Config Provider
 */

namespace Minds\Core\Config;

use Minds\Core\Di\Provider;

class ConfigProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Config', function ($di) {
            return new Config();
        }, ['useFactory'=>true]);
    }
}
