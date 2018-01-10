<?php

namespace Minds\Controllers\Cli;

use DateTime;
use Elasticsearch\ClientBuilder;
use Minds\Cli;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Helpers\Flags;
use Minds\Interfaces;

class Analytics extends Cli\Controller implements Interfaces\CliControllerInterface
{

    private $start;
    private $elasticsearch;

    public function help($command = null)
    {
        $this->out('Syntax usage: cli trending <type>');
    }

    public function exec()
    {
    }

    public function counts()
    {
        $interval = $this->getOpt('interval') ?: 'day';
        $from = $this->getOpt('from') ?: (strtotime('-24 hours') * 1000);
        $user = new Entities\User();
        $user->guid = $this->getOpt('guid');

        $this->out("Getting analytics for $user->guid");

        $manager = new Core\Analytics\Manager();
        $manager->setUser($user)
            ->setFrom($from)
            ->setInterval($interval);
        $result = $manager->getCounts();

        var_dump($result);
    }

}
