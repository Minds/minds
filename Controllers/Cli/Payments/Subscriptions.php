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
        // Initialize events
        \Minds\Core\Events\Defaults::_();

        /** @var Queue $queue */
        $queue = Di::_()->get('Payments\Subscriptions\Queue');

        $rows = $queue->get(time());

        foreach ($rows as $row) {
            $row = Cql::parseQueueRowTypes($row);

            $this->out("Processing subscription `{$row->getId()}`");
            $this->out('- Billing was scheduled for ' . date('c', $row->getNextBilling()));

            try {
                $processed = Dispatcher::trigger('subscriptions:process', $row->getPlanId(), [
                    'subscription' => $row
                ]);

                if ($processed) {
                    // $queue->processed($row);
                    $this->out('- OK!');
                } else {
                    $this->out("- Failed to process subscription `{$row->getId()}`");
                    // TODO: check age and cancel subscription if > 15 days. Send a notification to user?
                }
            } catch (\Exception $e) {
                $this->out("- Failed to process subscription `{$row->getId()}`");
                $this->out([ get_class($e) . ": {$e->getMessage()}", $e->getTraceAsString() ]);
            }

            $this->out('');
        }

        $this->out("Done");
    }
}
