<?php

namespace Minds\Controllers\Cli;

use DateTime;
use Elasticsearch\ClientBuilder;
use Minds\Cli;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Util\BigNumber;
use Minds\Entities;
use Minds\Helpers\Flags;
use Minds\Interfaces;
use Minds\Core\Rewards\Contributions\UsersIterator;
use Minds\Core\Events\Dispatcher;

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

        $total = BigNumber::_(0);
        $i = 0;
        foreach ($users as $guid) {
            $i++;
            $from = $this->getOpt('from') ?: (strtotime('-7 days') * 1000);
            if (!$guid) {
                continue;
            }
            try {
                $manager = new Core\Rewards\Manager();
                $manager->setFrom($timestamp)
                    ->setTo($to);
                $user = new Entities\User();
                $user->guid = (string) $guid;
                $manager->setUser($user);
    //            $manager->setDryRun(true);
                $reward = $manager->sync();
                $total = $total->add($reward->getAmount());

                /*Dispatcher::trigger('notification', 'contributions', [
                    'to' => [$user->guid],
                    'from' => 100000000000000519,
                    'notification_view' => 'contributions',
                    'params' => [ 'amount' => (string) BigNumber::_($reward->getAmount()) ],
                    'message' => ''
                    ]);*/

                echo "\r [$i][$guid]: synced past 48 hours. $total";
            } catch (\Exception $e) {
                echo "\r [$i][$guid]: failed synced past 48 hours. $total";
            }
        }
    }
    
    public function issue()
    {
        $username = $this->getOpt('username');
        $user = new Entities\User($username);
        
        $amount = BigNumber::toPlain($this->getOpt('amount'), 18);

        $offChainTransactions = Di::_()->get('Blockchain\Wallets\OffChain\Transactions');
        $offChainTransactions
            ->setType('test')
            ->setUser($user)
            ->setAmount((string) $amount)
            ->create();

        $this->out('Issued');
    }

}
