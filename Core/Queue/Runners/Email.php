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
        $mailer = new Core\Email\Mailer();
        $client = Queue\Client::Build();
        $client->setExchange("mindsqueue", "direct")
               ->setQueue("Email")
               ->receive(function ($data) use ($mailer) {
                  echo "[email]: Received an email \n";

                  $data = $data->getData();

                  $message = unserialize($data['message']);

                  $mailer->send($message);

                  echo "[email]: delivered to {$message->to[0]['name']} ($message->subject) \n";

               });
        $this->run();
    }
}
