<?php
namespace Minds\Core\Config;

use Minds\Core\Di\Provider;

/**
 * Minds Config Providers
 */
class ConfigProvider extends Provider
{
    /**
     * Registers providers onto DI
     * @return null
     */
    public function register()
    {
        $this->di->bind('Config', function ($di) {
            return new Config();
        }, ['useFactory'=>true]);
    }
}
