<?php
/**
 * Email module.
 */

namespace Minds\Core\Email;

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
