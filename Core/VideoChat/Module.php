<?php
/**
 * VideoChat module
 */
namespace Minds\Core\VideoChat;

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