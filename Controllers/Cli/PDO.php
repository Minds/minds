<?php

namespace Minds\Controllers\Cli;

use Minds\Core;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Exceptions;
use Minds\Entities;

class PDO extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function __construct()
    {
    }

    public function help($command = null)
    {
        $this->out('TBD');
    }
    
    public function exec()
    {
        $dwh = Core\Di\Di::_()->get('Database\PDO');
        $resp = $dwh->exec('SELECT * FROM suggested');
        var_dump($resp);
    }

}
