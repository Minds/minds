<?php
/**
 * Subscriptions module.
 */

namespace Minds\Core\Subscriptions;

use Minds\Interfaces\ModuleInterface;

class Module implements ModuleInterface
{
    /**
     * OnInit.
     */
    public function onInit()
    {
        $provider = new Provider();
        $provider->register();
    }
}
