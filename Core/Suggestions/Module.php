<?php
/**
 * Suggestions module.
 */

namespace Minds\Core\Suggestions;

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
