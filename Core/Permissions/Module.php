<?php
/**
 * Permissions module.
 */

namespace Minds\Core\Permissions;

use Minds\Interfaces\ModuleInterface;

class Module implements ModuleInterface
{
    public function onInit()
    {
        $provider = new Provider();
        $provider->register();
    }
}
