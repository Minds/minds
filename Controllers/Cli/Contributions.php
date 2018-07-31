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

        $from = $this->getOpt('from');

        if (!$from && $this->getOpt('incremental')) {
            $from = strtotime('midnight') * 1000; //run throughout the day, provides estimates
        } else {
            $from = strtotime('midnight -1 day') * 1000;
        }

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
            $user = new Entities\User((string) $guid, false);

            if (!$user->getPhoneNumberHash()) {
                // Avoid users without a phone number hash
                continue;
            }

            $manager = new Core\Rewards\Contributions\Manager();
            $manager->setFrom($from)
                ->setUser($user);
            //$manager->setDryRun(true);
            $results = $manager->sync();

            foreach ($results as $result) {
                $total += (int) $result->getAmount();
            }

            echo "\r [$i][$guid]: synced past 48 hours. $total";
        }
    }

    public function syncCheckins()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $from = $this->getOpt('from');

        if (!$from && $this->getOpt('incremental')) {
            $from = strtotime('midnight') * 1000; //run throughout the day, provides estimates
        } else {
            $from = strtotime('midnight -1 day') * 1000;
        }

        $hours = 24;
        $checkins = []; //user_guid => $amount
        
        for ($h = 0; $h < $hours; $h++) {
            $h_from = strtotime("+$h hours", $from / 1000) * 1000;
            $h_to = strtotime("+1 hour", $h_from / 1000) * 1000;
            
            if ($h_to > time() * 1000) {
                break; // skip
            }

            echo "\n$h $h_from -> $h_to\n";
            $users = new UsersIterator;
            $users->setFrom($h_from)
                  ->setTo($h_to)
                  ->setAction('active');

            $hashes = [];
            $total = 0;
            $i = 0;
            $duplicates = 0;
            foreach ($users as $guid) {
                $i++;
                if (!$guid) {
                    continue;
                }
                $user = new Entities\User((string) $guid, false);
                $hash = $user->getPhoneNumberHash();

                if (isset($hashes[$hash])) { //don't allow multiple phones to claim checkin
                    $duplicates++; 
                    continue;
                }
                $hashes[$hash] = true;
                if (!isset($checkins[$user->guid])) {
                    $checkins[$user->guid] = [
                        'user' => $user,
                        'amount' => 0,
                    ];
                }

                $checkins[$user->guid]['amount']++;

                echo "\r[$h][$i][$guid]: synced. duplicates: $duplicates";
            }
        }

        foreach ($checkins as $checkin) {
            $manager = new Core\Rewards\Contributions\Manager();
            $manager->setFrom($from)
                    ->setUser($checkin['user'])
                    ->issueCheckins($checkin['amount']);
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
