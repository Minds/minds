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
use Minds\Core\Rewards\Contributions\UsersIterator;

class Contributions extends Cli\Controller implements Interfaces\CliControllerInterface
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

    public function sync()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $from = $this->getOpt('from') ?: (strtotime('midnight -48 hours') * 1000);

        $users = new UsersIterator;
        $users->setFrom($from);

        if ($action = $this->getOpt('action')) {
            $users->setAction($action);
        }

        $this->out("Getting rewards for all users");

        $total = 0; 
        $i = 0;
        foreach ($users as $guid) {
            $i++;
            if (!$guid) {
                continue;
            }
            $manager = new Core\Rewards\Contributions\Manager();
            $manager->setFrom($from);
            $user = new Entities\User();
            $user->guid = (string) $guid;
            $manager->setUser($user);
            //$manager->setDryRun(true);
            $results = $manager->sync();

            foreach ($results as $result) {
                $total += (int) $result->getAmount();
            }

            echo "\r [$i][$guid]: synced past 48 hours. $total";
        }
    }

    public function test()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $from = $this->getOpt('from') ?: (strtotime('-7 days') * 1000);
        $user = new Entities\User();
        $user->guid = $this->getOpt('guid');

        $this->out("Getting rewards for $user->guid");

        $manager = new Core\Rewards\Contributions\Manager();
        $manager
            ->setFrom($from)
            ->setDryRun(true);

        if ($user->guid) {
             $manager->setUser($user);
        }
        $results = $manager->sync();

        $totals = 0;
        $totals_by_time = [];
        foreach ($results as $result) {
            $totals += $result->getAmount();
            $totals_by_type[$result->getMetric()] += $result->getAmount();
        } 
        var_dump($totals);
        var_dump($totals_by_type);
    }

}
