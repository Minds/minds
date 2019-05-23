<?php
/**
 * Steward module.
 */

namespace Minds\Core\Steward;

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

        $events = new Events();
        $events->register();
    }
}
