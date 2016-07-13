<?php

namespace Minds\Controllers\Cli;

use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Cli;

class Install extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function __construct()
    {
        define('__MINDS_INSTALLING__', true);
    }

    public function help($command = null)
    {
        $this->out('TBD');
    }
    
    public function exec()
    {

    }
}
