<?php
/**
 * Cleanup Dispatcher - Used by
 */
namespace Minds\Core\Queue\Runners;

use Minds\Core\Queue\Interfaces;
use Minds\Core\Queue;
use Minds\Core\Events\Dispatcher;

/**
* Queued Notifications
*/
class CleanupDispatcher implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Queue\Client::Build();
        $client
        ->setQueue("CleanupDispatcher")
        ->receive(function ($data) {
            $type = isset($data['type']) ? $data['type'] : 'all';
            Dispatcher::trigger('cleanup:dispatch', $type, $data);
        });
    }
}
