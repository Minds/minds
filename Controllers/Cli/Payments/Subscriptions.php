<?php

namespace Minds\Controllers\Cli\Payments;

use Minds\Cli;
use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Payments\Subscriptions\Manager;
use Minds\Core\Payments\Subscriptions\Queue;
use Minds\Helpers\Cql;
use Minds\Interfaces;

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
}
