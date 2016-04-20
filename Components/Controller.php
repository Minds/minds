<?php
/**
 * Controller class
 */

namespace Minds\Components;

use Minds\Core\Di\Di;

class Controller {

    protected $di;
    protected $config;

    public function __construct()
    {
        $this->di = Di::_();
        $this->config = $this->di->get('Config');
    }

}
