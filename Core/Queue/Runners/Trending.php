<?php


namespace Minds\Core\Queue\Runners;

use Minds\Core;
use Minds\Core\Queue\Interfaces;

class Trending implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Core\Queue\Client::Build();
        $client->setQueue("Trending")
            ->receive(function ($data) {
                echo "Received a compile trending request \n";
                $manager = new Core\Trending\Manager();
                $manager->run();
            });
    }

}