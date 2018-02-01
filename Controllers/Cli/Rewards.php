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

class Rewards extends Cli\Controller implements Interfaces\CliControllerInterface
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


        $timestamp = $this->getOpt('timestamp') ?: (strtotime('midnight -24 hours') * 1000);
        $to = strtotime("+24 hours", $timestamp / 1000) * 1000;

        $users = new UsersIterator;
        $users->setFrom($timestamp)
            ->setTo($to);

        if ($action = $this->getOpt('action')) {
            $users->setAction($action);
        }

        $this->out("Getting rewards for all users");

        $total = 0; 
        $i = 0;
        foreach ($users as $guid) {
            $i++;
            $from = $this->getOpt('from') ?: (strtotime('-7 days') * 1000);
            if (!$guid) {
                continue;
            }
            $manager = new Core\Rewards\Manager();
            $manager->setFrom($timestamp)
                ->setTo($to);
            $user = new Entities\User();
            $user->guid = (string) $guid;
            $manager->setUser($user);
//            $manager->setDryRun(true);
            $reward = $manager->sync();
            $total += (int) $reward->getAmount();

            echo "\r [$i][$guid]: synced past 48 hours. $total";
        }
    }
    
    public function issue()
    {
        $username = $this->getOpt('username');
        $user = new Entities\User($username);
        
        $amount = $this->getOpt('amount') * 10 ** 18;

        $offChainTransactions = Di::_()->get('Blockchain\Wallets\OffChain\Transactions');
        $offChainTransactions
            ->setType('test')
            ->setUser($user)
            ->setAmount($amount)
            ->create();

        $this->out('Issued');
    }

}
