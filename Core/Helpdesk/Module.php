<?php
/**
 * Helpdesk module
 */
namespace Minds\Core\Helpdesk;

use Minds\Interfaces\ModuleInterface;

class Module implements ModuleInterface
{

    /**
     * OnInit
     */
    public function onInit()
    {
        $provider = new Provider();
        $provider->register();
    }

}