<?php
namespace Minds\Core\Queue\Runners;

use Minds\Core\Queue\Interfaces;
use Minds\Core\Queue;
use Minds\Core\Events\Dispatcher;

/**
* Queued Subscriptions
*/
class SubscriptionDispatcher implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Queue\Client::Build();
        $client->setExchange('mindsqueue', 'direct')
            ->setQueue('SubscriptionDispatcher')
            ->receive(function ($data) {
                $data = $data->getData();
                echo "\nDispatching async subscription for {$data['currentUser']}...";
                $result = Dispatcher::trigger('subscription:dispatch', 'all', $data);

                // print_r($result);
            });
    }
}
