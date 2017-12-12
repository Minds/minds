<?php

namespace Minds\Controllers\Cli\Payments;

use Minds\Cli;
use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Payments\RecurringSubscriptions\Manager;
use Minds\Core\Payments\RecurringSubscriptions\Queue;
use Minds\Helpers\Cql;
use Minds\Interfaces;

class RecurringSubscriptions extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function help($command = null)
    {
        $this->out('Syntax usage: payments recurring_subscriptions [run]');
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
        $queue = Di::_()->get('Payments\RecurringSubscriptions\Queue');

        $rows = $queue->get(time());

        foreach ($rows as $row) {
            $row = Cql::parseQueueRowTypes($row);

            $this->out("Processing subscription `{$row['subscription_id']}`");
            $this->out('- Billing was scheduled for ' . date('c', $row['next_billing']));

            try {
                $processed = Dispatcher::trigger('recurring-subscriptions:process', $row['type'], [
                    'recurring_subscription' => $row
                ]);

                if ($processed) {
                    $queue->processed($row);
                    $this->out('- OK!');
                } else {
                    $this->out("- Failed to process subscription `{$row['subscription_id']}`");
                    // TODO: check age and cancel subscription if > 15 days. Send a notification to user?
                }
            } catch (\Exception $e) {
                $this->out("- Failed to process subscription `{$row['subscription_id']}`");
                $this->out([ get_class($e) . ": {$e->getMessage()}", $e->getTraceAsString() ]);
            }

            $this->out('');
        }

        $this->out("Done");
    }
}
