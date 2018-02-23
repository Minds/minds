<?php


namespace Minds\Core\Queue\Runners;


use Minds\Core\Di\Di;
use Minds\Core\Email\EmailSubscription;
use Minds\Core\Email\Repository;
use Minds\Core\Queue;
use Minds\Core\Queue\Interfaces\QueueRunner;

class Registered implements QueueRunner
{
    public function run()
    {
        $config = Di::_()->get('Config');
        $subscriptions = $config->get('default_email_subscriptions');
        /** @var Repository $repository */
        $repository = Di::_()->get('Email\Repository');

        $client = Queue\Client::Build();
        $client->setQueue("Registered")
            ->receive(function ($data) use ($subscriptions, $repository) {
                echo "[registered]: User registered \n";

                $user_guid = unserialize($data['user_guid']);

                foreach ($subscriptions as $subscription) {
                    $sub = array_merge($subscription, ['user_guid' => $user_guid]);
                    $repository->add(new EmailSubscription($sub));
                }

                echo "[registered]: subscribed {$user_guid} to default email notifications \n";
            });
        $this->run();
    }
}