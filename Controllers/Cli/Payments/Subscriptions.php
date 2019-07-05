<?php

namespace Minds\Controllers\Cli\Payments;

use Minds\Cli;
use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Payments\Subscriptions\Manager;
use Minds\Core\Payments\Subscriptions\Queue;
use Minds\Core\Security\ACL;
use Minds\Helpers\Cql;
use Minds\Interfaces;
use Minds\Core\Util\BigNumber;

class Subscriptions extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function help($command = null)
    {
        $this->out('Syntax usage: payments subscriptions [run]');
    }

    public function exec()
    {
        $this->help();
    }

    public function run()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Initialize events
        \Minds\Core\Events\Defaults::_();

        ACL::$ignore = true; // we need to save to channels

        /** @var Manager $manager */
        $manager = Di::_()->get('Payments\Subscriptions\Manager');

        /** @var Queue $queue */
        $subscriptions = Di::_()->get('Payments\Subscriptions\Iterator');
        $subscriptions->setFrom(time())
            ->setPaymentMethod('tokens')
            ->setPlanId('wire');

        foreach ($subscriptions as $subscription) {
            $this->out("Subscription:`{$subscription->getId()}`");
            $billing = date('d-m-Y', $subscription->getNextBilling());
            
            $user_guid = $subscription->getUser()->guid;
            $this->out("\t$billing | $user_guid");

            if (!$this->getOpt('dry-run')) {
                $this->out("\t CHARGED");
                $manager->setSubscription($subscription);
                $manager->charge();
            }
        }
        
        $this->out("Done");
    }

    public function repair()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);   

        /** @var Manager $manager */
        $manager = Di::_()->get('Payments\Subscriptions\Manager');

        /** @var Queue $queue */
        $subscriptions = Di::_()->get('Payments\Subscriptions\Iterator');
        $subscriptions->setFrom(0)
            ->setPaymentMethod('tokens')
            ->setPlanId('wire');

        foreach ($subscriptions as $subscription) {
            $this->out("Subscription:`{$subscription->getId()}`");
            if ($subscription->getId() === 'offchain') {
                $this->out("Subscription:`{$subscription->getId()}` needs repairing");

                $urn = "urn:subscription:" . implode('-', [
                    $subscription->getId(),
                    $subscription->getUser()->getGuid(),
                    $subscription->getEntity()->getGuid(),
                ]);
                $this->out("Subscription:`{$subscription->getId()}` needs repairing to $urn");
                $manager->setSubscription($subscription);
                $manager->cancel();
                $subscription->setId($urn);
                $manager->setSubscription($subscription);
                $manager->create();
            }
            if (strpos($subscription->getId(), '0x', 0) === 0) {
                $this->out("Subscription:`{$subscription->getId()}` needs repairing");
                $urn = "urn:subscription:" . implode('-', [
                    $subscription->getId(),
                    $subscription->getUser()->getGuid(),
                    $subscription->getEntity()->getGuid(),
                ]);

                $this->out("Subscription:`{$subscription->getId()}` needs repairing to $urn");
                $manager->setSubscription($subscription);
                                $manager->cancel();
                $subscription->setId($urn);
                $manager->setSubscription($subscription);
                $manager->create();
            }
        }

        $this->out("Done"); 
    }

    /**
     * Sometimes, plus doesn't hit the delegate so the badge
     * doesn't apply. This is designed to run regularly via
     * a cron job to fix that
     * @return void
     */
    public function fixPlusWires()
    {
        ACL::$ignore = true; // we need to save to channels
        $delegate = new \Minds\Core\Wire\Delegates\Plus;
        $usersLastPlus = [];
        foreach ($this->getWires(false) as $wire) {
            $sender_guid = $wire->getSender()->getGuid();
            $friendly = date('d-m-Y', $wire->getTimestamp());
            echo "\n$sender_guid";
            if ($wire->getTimestamp() < $usersLastPlus[$sender_guid] ?? time()) {
                echo " $friendly already given plus to this user";
                continue;
            }
            $usersLastPlus[$sender_guid] = $wire->getTimestamp();
            $friendly = date('d-m-Y', $wire->getTimestamp());
            echo " $friendly sending plus update ({$wire->getAmount()})";

            if ($delegate->onWire($wire, 'offchain') || $delegate->onWire($wire, '0x6f2548b1bee178a49c8ea09be6845f6aeaf3e8da')) {
                echo " done";
            }
        }
    }

    public function getWires($onchain = false)
    {
        $cql = \Minds\Core\Di\Di::_()->get('Database\Cassandra\Cql');

        $prepared = new \Minds\Core\Data\Cassandra\Prepared\Custom;

        $statement = "SELECT * FROM blockchain_transactions_mainnet WHERE contract='offchain:wire' and user_guid=? ALLOW FILTERING";
        if ($onchain) {
            $statement = "SELECT * FROM blockchain_transactions_mainnet WHERE wallet_address=? ALLOW FILTERING";
        } else {
            $statement = "SELECT * FROM blockchain_transactions_mainnet WHERE user_guid=? and amount>=? ALLOW FILTERING";
        }

        $offset = "";

        while (true) {
            if ($onchain) {
                $prepared->query($statement, [ '0x6f2548b1bee178a49c8ea09be6845f6aeaf3e8da' ]);
            } else {
                $prepared->query($statement, [ new \Cassandra\Varint(730071191229833224), new \Cassandra\Varint(5) ]);
            }

            $prepared->setOpts([
                'paging_state_token' => $offset,
                'page_size' => 100,
            ]);

            try {
                $result = $cql->request($prepared);
                if (!$result) {
                    break;
                }

                $offset = $result->pagingStateToken();
            } catch (\Exception $e) {
                var_dump($e);
            }
            foreach ($result as $row) {
                $data = json_decode($row['data'], true);

                if ($row['timestamp']->time() < strtotime('35 days ago')) {
                    return; // Do not sync old
                }

                if (!$data['sender_guid']) {
                    var_dump($row);
                }
                $wire = new \Minds\Core\Wire\Wire();
                $wire
                    ->setSender(new \Minds\Entities\User($data['sender_guid']))
                    ->setReceiver(new \Minds\Entities\User($data['receiver_guid']))
                    ->setEntity(\Minds\Entities\Factory::build($data['entity_guid']))
                    ->setAmount((string) $data['amount'])
                    ->setTimestamp((int) $row['timestamp']->time());
                yield $wire;
            }
        }
    }
}
