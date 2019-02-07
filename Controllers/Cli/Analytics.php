<?php

namespace Minds\Controllers\Cli;

use Elasticsearch\ClientBuilder;
use Minds\Cli;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Helpers\Flags;
use Minds\Interfaces;
use Minds\Entities\User;

class Analytics extends Cli\Controller implements Interfaces\CliControllerInterface
{

    private $start;
    private $elasticsearch;

    public function help($command = null)
    {
        $this->out($command);
        switch($command) {
            case 'sync_activeUsers': 
                $this->out('Indexes user activity by guid and counts per day');
                $this->out('--from={timestamp} the day to start counting. Default is yesterday at midnight');
                $$his->out('--to={timestamp} the day to stop counting. Default is yesterday at midnight');
                $this->out('--rangeOffset={number of days} the number of days to look back into the past. Default is 7');
                break;
            case 'counts':
                $this->out('Prints the counts of a user');
                $this->out('--from={timestamp in milliseconds} the day to start count. Default is yesterday');
                $this->out('--guid={user guid} REQUIRED the user to aggregate');
            default:
                $this->out('Syntax usage: cli analytics <type>');
                $this->out('Available types: sync_activeUsers, counts');
                $this->out('Command specific help: help analytics <type>');
        }
        
    }

    public function exec()
    {
    }

    public function sync_activeUsers()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $from = (strtotime("midnight", $this->getOpt('from')) ?: strtotime('midnight yesterday'));
        $to = (strtotime("midnight", $this->getOpt('to')) ?: strtotime('midnight yesterday'));
        $rangeOffset = getOpt('rangeOffset') ?: 7;

        $this->out('Collecting user activity');
        while ($from <= $to) {
            $this->out('Syncing for ' . gmdate('c', $from));
            $manager = new Core\Analytics\UserStates\Manager();
            $manager->setReferenceDate($from)
                ->setRangeOffset($rangeOffset)
                ->sync();
            $manager->emitStateChanges();
            $from = strtotime("+1 day", $from);
        }
        $this->out('Completed syncing user activity');
    }

    public function counts()
    {
        $interval = $this->getOpt('interval') ?: 'day';
        $from = $this->getOpt('from') ?: (strtotime('-24 hours') * 1000);
        $user = new Entities\User();
        $user->guid = $this->getOpt('guid');
        
        $manager = new Core\Analytics\Manager();
        $manager->setUser($user)
            ->setFrom($from)
            ->setInterval($interval);
        $result = $manager->getCounts();

        var_dump($result);
    }

}
