<?php

namespace Minds\Controllers\Cli;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Entities;

class Moderation extends Cli\Controller implements Interfaces\CliControllerInterface
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
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    public function runVerdicts()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $manager = Di::_()->get('Moderation\Verdict\Manager');
        $manager->run($this->getOpt('jury') ?? 'initial');
    }

}
