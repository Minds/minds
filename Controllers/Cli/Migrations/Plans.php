<?php

namespace Minds\Controllers\Cli\Migrations;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Exceptions;
use Minds\Core\Data\Cassandra\Prepared;

use Cassandra;
use Nette\Neon\Exception;

class Plans extends Cli\Controller implements Interfaces\CliControllerInterface
{
    private static $limit = 72;

    public function __construct()
    {
    }

    public function help($command = null)
    {
        $this->out('Syntax usage: cli migrations reports');
    }

    public function exec()
    {
        $this->out('Start migration?', $this::OUTPUT_INLINE);
        $answer = trim(readline('[y/N] '));

        if ($answer != 'y') {
            throw new Exceptions\CliException('Cancelled by user');
        }

        /** @var Core\Data\Cassandra\Client $client */
        $client = Di::_()->get('Database\Cassandra\Cql');

        /** @var Core\Payments\Subscriptions\Repository $repo */
        $repo = Di::_()->get('Payments\Subscriptions\Repository');

        /** @var Core\Payments\Stripe\Stripe $stripe */
        $stripe = Di::_()->get('StripePayments');

        $select = new Prepared\Custom();
        $select->query("SELECT * FROM plans WHERE status = ? ALLOW FILTERING", [ 'active' ]);

        $rows = $client->request($select);

        foreach ($rows as $row) {
            $throttle = false;

            try {
                $this->out("Migrating plan {$row['plan']} ({$row['user_guid']} -> {$row['entity_guid']})…");

                $data = [
                    'status' => 'active',
                    'recurring' => 'monthly'
                ];

                if ($row['subscription_id']) {
                    $this->out("Subscription: {$row['subscription_id']}");

                    $data['subscription_id'] = $row['subscription_id'];

                    if (strpos($row['subscription_id'], 'sub_') === 0) {
                        // Stripe
                        $sub = new Core\Payments\Subscriptions\Subscription();
                        $sub
                            ->setId($row['subscription_id']);

                        $stripeSub = $stripe->getSubscription($sub);
                        $throttle = true;

                        if ($stripeSub) {
                            $data['next_billing'] = $stripeSub->getNextBillingDate();
                            $data['last_billing'] = strtotime('-1 month', $data['next_billing']);
                        }
                    }
                }

                if ($row['amount']) {
                    $data['amount'] = (double) $row['amount'];
                }

                $repo->upsert(
                    $row['plan'],
                    'money',
                    $row['entity_guid'],
                    $row['user_guid'],
                    $data
                );

                $this->out(['Ok…', '']);
            } catch (\Exception $e) {
                $this->out(["Caught Exception: {$e->getMessage()}", '']);
            } finally {
                if ($throttle) {
                    usleep(500 * 1000);
                }
            }
        }

        $this->out('Done!');
    }
}
