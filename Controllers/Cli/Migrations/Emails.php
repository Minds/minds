<?php


namespace Minds\Controllers\Cli\Migrations;

use Minds\Cli;
use Minds\Core;
use Minds\Entities\User;
use Minds\Interfaces;


class Emails extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function __construct()
    {
    }

    public function help($command = null)
    {
        $this->out('Syntax usage: cli migrations search [dev]');
    }

    public function exec()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $offset = $this->getOpt('offset') ?: '';

        $this->out("Running migrations for users");

        $subscribeBatch = Core\Email\Batches\Factory::build('subscribe');
        $subscribeBatch->setOffset($offset)
            ->run();
    }
}