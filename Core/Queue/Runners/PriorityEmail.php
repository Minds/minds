<?php

namespace Minds\Core\Queue\Runners;

use Minds\Core;
use Minds\Core\Queue;
use Minds\Core\Queue\Interfaces;

/**
 * Priority Email queue runner.
 * Similar as the Email queue runner but for priority emails
 */
class PriorityEmail implements Interfaces\QueueRunner
{
    public function run()
    {
        $mailer = new Core\Email\Mailer();
        $client = Queue\Client::Build();
        $client->setQueue("PriorityEmail")
            ->receive(function ($data) use ($mailer) {
                echo "[email]: Received a priority email \n";

                $data = $data->getData();

                $message = unserialize($data['message']);

                $mailer->send($message);

                echo "[priority email]: delivered to {$message->to[0]['name']} ($message->subject) \n";
            });
        $this->run();
    }
}
