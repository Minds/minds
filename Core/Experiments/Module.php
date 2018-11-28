<?php
/**
 * Experiments module
 */
namespace Minds\Core\Experiments;

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