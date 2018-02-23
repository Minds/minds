<?php


namespace Minds\Core\Email\Batches;


use Minds\Core\Email\Campaigns\WhenNotifications;
use Minds\Core\Email\EmailSubscribersIterator;
use Minds\Core\Notification\Counters;

class Notifications implements EmailBatchInterface
{
    protected $offset;

    public function setOffset($offset)
    {
        $this->offset = isset($offset) ?: '';
        return $this;
    }


    public function run()
    {
        $counters = new Counters();

        $iterator = new EmailSubscribersIterator();
        $iterator->setCampaign('when')
            ->setTopic('unread_notifications')
            ->setValue(true)
            ->setOffset($this->offset);

        foreach ($iterator as $user) {
            $counters->setUser($user->guid);

            if ($counters->getCount() >= 0) {
                $campaign = new WhenNotifications();

                $campaign->setUser($user)
                    ->send();
            }
        }
    }
}