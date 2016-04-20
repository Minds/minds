<?php
/**
 * Controller class
 */

namespace Minds\Components;

class Controller {

    protected $di;
    protected $config;

    public function __construct()
    {
        $this->di = Minds\Core\Di\Di::_();
        $this->config = $this->di->get('Config');
    }

}
