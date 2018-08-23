<?php
namespace Minds\Core\Queue\Runners;

use Minds\Core\Queue\Interfaces;
use Minds\Core\Queue;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Core\Channels\Delegates\DeleteArtifacts;

class ChannelDeleteArtifactsCleanup implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Queue\Client::Build();
        $client->setQueue("ChannelDeleteArtifactsCleanup")
               ->receive(function ($data) {
                   echo "Received a channel delete feed cleanup request \n";

                   $data = $data->getData();
                   
                   $deleteArtifacts = new DeleteArtifacts;
                   $deleteArtifacts->delete($data['user_guid']);
               });
    }
}
