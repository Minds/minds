<?php
namespace Minds\Components;

use Minds\Core\Di\Di;

/**
 * API Controller
 * @todo Move to Minds\Api namespace (to reflect Cli structure).
 * @todo Create a BaseController class (to be used on Api, Cli, etc) with core DI operations.
 * @todo Ensure this class is used EVERYWHERE on Minds\Controllers\api
 */
class Controller {
    protected $di;
    protected $config;

    public function __construct()
    {
        $this->di = Di::_();
        $this->config = $this->di->get('Config');
    }
}
