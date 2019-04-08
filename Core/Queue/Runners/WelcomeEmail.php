<?php

namespace Minds\Core\Queue\Runners;

use Minds\Core\Queue\Interfaces;
use Minds\Core\Queue;
use Minds\Core\Events\Dispatcher;

/**
 * Welcome email runner. Delayed.
 */
class WelcomeEmail implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Queue\Client::Build();
        $client->setQueue('WelcomeEmail')
               ->receive(function ($data) {
                   $result = Dispatcher::trigger('welcome_email', 'all', $data->getData());
               });
    }
}
