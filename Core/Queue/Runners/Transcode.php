<?php
namespace Minds\Core\Queue\Runners;

use Minds\Core;
use Minds\Core\Queue\Interfaces;

class Transcode implements Interfaces\QueueRunner
{
    public function run()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $client = Core\Queue\Client::Build();
        $client->setQueue("Transcode")
            ->receive(function ($data) {
                echo "Received a transcode request \n";
                $transcoder = new Core\Media\Services\FFMpeg();
                $transcoder->setKey($data->getData()['key']);
                $transcoder->onQueue();
            });
    }

}
