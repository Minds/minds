<?php
namespace Minds\Core\Queue\Runners;

use Minds\Core\Queue\Interfaces;
use Minds\Core\Queue;
use Minds\Core\Events\Dispatcher;

/**
* Queued Notifications
*/
class NotificationDispatcher implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Queue\Client::Build();
        $client->setQueue("NotificationDispatcher")
            ->receive(function ($data) {
                $data = $data->getData();
                $type = isset($data['type']) ? $data['type'] : 'entity';
                Dispatcher::trigger('notification:dispatch', $type, $data);
            });
    }
}
