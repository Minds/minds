<?php
namespace Minds\Core\Queue\Runners;

use Minds\Core;
use Minds\Core\Data;
use Minds\Core\Queue\Interfaces;
use Minds\Core\Queue;
use Minds\Core\Notification\Settings;
use Minds\Entities\User;
use Surge;

/**
 * Email queue runner
 */

class Email implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Queue\Client::Build();
        $client->setExchange("mindsqueue", "direct")
               ->setQueue("Email")
               ->receive(function ($data) {
                  echo "[email]: Received an email \n";

                  $data = $data->getData();

                  $message = unserialize($data['message']);

                  $mailer = new Core\Email\Mailer();
                  $mailer->send($message);

                  echo "[email]: delivered to {$message->to[0]['name']} \n";

               });
        $this->run();
    }
}
