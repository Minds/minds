<?php

namespace Minds\Controllers\Cli;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Exceptions;
use Minds\Entities;
use Stripe;

class SubscriptionSync extends Cli\Controller implements Interfaces\CliControllerInterface
{

    private $db;

    public function __construct()
    {
        $this->db = Di::_()->get('Database\Cassandra\Cql');
    }

    public function help($command = null)
    {
        $this->out('TBD');
    }

    public function exec()
    {
        $this->out('Missing subcommand');
    }

    public function stripe()
    {
        $this->db = Di::_()->get('Database\Cassandra\Cql'); //construct not being hit?

        Stripe\Stripe::setApiKey(Core\Config::_()->payments['stripe']['api_key']);
        $query = new Core\Data\Cassandra\Prepared\Custom();
        $query->query("SELECT * FROM plans");

        $plans = $this->db->request($query);

        foreach ($plans as $plan) {

            $opts = [];
            if ($plan['entity_guid']) {
                $entity = Core\Entities::build($plan['entity_guid']);
                if ($entity->type != 'user') {
                  $entity = $entity->getOwnerEntity();
                }
                $opts = [
                  'stripe_account' => $entity->getMerchant()['id']
                ];
            }

            try {
                $result = Stripe\Subscription::retrieve($plan['subscription_id']);
                $amount = ($result->quantity * $result->plan->amount) / 100;

                $insert = new Core\Data\Cassandra\Prepared\Custom();
                $insert->query("INSERT INTO plans (user_guid, plan, entity_guid, amount) VALUES (?, ?, ?, ?)",
                [
                  $plan['user_guid'],
                  $plan['plan'],
                  $plan['entity_guid'],
                  (int) $amount
                ]);
                $this->db->request($insert);

                $this->out("{$plan['subscription_id']} synced with $amount");
            } catch (\Exception $e) {
                echo $e->getMessage();
            }

        }

        $this->out("Done");
    }
}
