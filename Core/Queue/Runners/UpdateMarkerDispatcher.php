<?php
namespace Minds\Core\Queue\Runners;

use Minds\Core\Queue\Interfaces;
use Minds\Core\Queue;
use Minds\Core\Events\Dispatcher;
use Minds\Entities\Group as GroupEntity;
use Minds\Core\Groups\Notifications;

/**
* Queued Groups Notifications
*/
class UpdateMarkerDispatcher implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Queue\Client::Build();
        $client->setQueue("UpdateMarkerDispatcher")
            ->receive(function ($data) {
                $data = $data->getData();
                $marker = unserialize($data['marker']);
                
                $group = new GroupEntity();
                $group->loadFromGuid($marker->getEntityGuid());


                echo "[]: updating markers for $group->guid \n";

                $notifications = (new Notifications)->setGroup($group);
                $notifications->send($marker);
                echo "(done)";
            });
    }
}
