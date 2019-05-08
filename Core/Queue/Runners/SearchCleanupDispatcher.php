<?php

/**
 * Search Index Cleanup
 *
 * @author emi
 */

namespace Minds\Core\Queue\Runners;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Queue;
use Minds\Core\Queue\Interfaces\QueueRunner;

class SearchCleanupDispatcher implements QueueRunner
{
    /**
     * Runs the queue
     */
    public function run()
    {
        $client = Queue\Client::build();

        $client
            ->setQueue("SearchCleanupDispatcher")
            ->receive(function (Queue\Message $message) {
                /** @var Core\Events\Dispatcher $dispatcher */
                $dispatcher = Di::_()->get('EventsDispatcher');

                var_dump($message);

                $data = $message->getData();
                $dispatcher->trigger('search:cleanup:dispatch', 'all', $data);
            });
    }
}
