<?php

namespace Minds\Controllers\Cli;

use Minds\Core\Minds;
use Minds\Core;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Exceptions;
use Minds\Entities;
use Minds\Core\Queue\Runners;

class QueueRunner extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function __construct()
    {
        $minds = new Minds();
        $minds->start();
    }

    public function help($command = null)
    {
        $this->out('TBD');
    }
    
    public function exec()
    {
        $this->out('Missing subcommand');
    }

    public function run()
    {
        $runner = $this->getOpt('runner');
        try{
            $this->out("Running $runner");
            $this->out("Press Ctrl + C to cancel");
            $runner = Runners\Factory::build($runner)->run();
        } catch(Exception $e){
            $this->out("Failed: " . $e->getMessage());
        }
    }
}
